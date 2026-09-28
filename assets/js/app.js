const IMGLY_MODULE_URL = new URL("../vendor/imgly/background-removal.mjs", import.meta.url).toString();
const MODELS_PUBLIC_PATH = new URL("../models/", import.meta.url).toString();

const dropzone = document.getElementById("dropzone");
const fileInput = document.getElementById("fileInput");
const dropzoneEmpty = document.getElementById("dropzoneEmpty");
const dropzonePreview = document.getElementById("dropzonePreview");
const previewImg = document.getElementById("previewImg");
const uploadActions = document.getElementById("uploadActions");
const startBtn = document.getElementById("startBtn");
const resetBtn = document.getElementById("resetBtn");
const progressBox = document.getElementById("progressBox");
const progressText = document.getElementById("progressText");
const progressFill = document.getElementById("progressFill");
const resultBox = document.getElementById("resultBox");
const resultImg = document.getElementById("resultImg");
const downloadBtn = document.getElementById("downloadBtn");
const errorBox = document.getElementById("errorBox");
const steps = document.querySelectorAll(".step");

let currentFile = null;
let previewUrl = null;
let resultUrl = null;
let removeBackgroundFn = null;
let preloadFn = null;
let sharedConfig = null;

function setStep(n) {
    steps.forEach((step) => {
        step.classList.toggle("active", Number(step.dataset.step) <= n);
    });
}

function showError(message) {
    errorBox.textContent = message;
    errorBox.hidden = false;
}

function clearError() {
    errorBox.hidden = true;
    errorBox.textContent = "";
}

// The card only ever shows one stage at a time: the upload/image section,
// the processing section, or the output section.
function showStage(stage) {
    dropzone.hidden = stage !== "upload";
    uploadActions.hidden = stage !== "upload";
    progressBox.hidden = stage !== "processing";
    resultBox.hidden = stage !== "result";
}

function resetAll() {
    if (previewUrl) URL.revokeObjectURL(previewUrl);
    if (resultUrl) URL.revokeObjectURL(resultUrl);
    previewUrl = null;
    resultUrl = null;
    currentFile = null;

    fileInput.value = "";
    dropzoneEmpty.hidden = false;
    dropzonePreview.hidden = true;
    previewImg.src = "";

    startBtn.disabled = true;

    progressFill.style.width = "0%";

    resultImg.src = "";
    downloadBtn.removeAttribute("href");

    clearError();
    showStage("upload");
    setStep(1);
}

function handleFile(file) {
    if (!file) return;
    if (!file.type.startsWith("image/")) {
        showError("That doesn't look like an image. Please choose a PNG, JPG, or WEBP file.");
        return;
    }

    clearError();
    if (resultUrl) {
        URL.revokeObjectURL(resultUrl);
        resultUrl = null;
    }

    if (previewUrl) URL.revokeObjectURL(previewUrl);
    currentFile = file;
    previewUrl = URL.createObjectURL(file);
    previewImg.src = previewUrl;

    dropzoneEmpty.hidden = true;
    dropzonePreview.hidden = false;

    startBtn.disabled = false;
    showStage("upload");

    setStep(2);
    warmUp();
}

async function loadImglyModule() {
    if (!removeBackgroundFn) {
        const mod = await import(IMGLY_MODULE_URL);
        removeBackgroundFn = mod.removeBackground || mod.default;
        preloadFn = mod.preload;
    }
    return { removeBackground: removeBackgroundFn, preload: preloadFn };
}

// Probe WebGPU once so both the warm-up preload and the real run agree on
// the same execution provider (matching configs are required for the
// library's internal preload cache to actually be reused at Start time).
async function detectDevice() {
    try {
        if (!navigator.gpu) return "cpu";
        const adapter = await navigator.gpu.requestAdapter();
        return adapter ? "gpu" : "cpu";
    } catch {
        return "cpu";
    }
}

function onRemovalProgress(key, current, total) {
    const pct = total ? Math.round((current / total) * 100) : 0;
    progressFill.style.width = pct + "%";
    progressText.textContent = "Removing background… " + pct + "%";
}

async function getSharedConfig() {
    if (!sharedConfig) {
        sharedConfig = {
            publicPath: MODELS_PUBLIC_PATH,
            model: "small",
            device: await detectDevice(),
            proxyToWorker: true,
            progress: onRemovalProgress,
        };
    }
    return sharedConfig;
}

// Kick off the WASM/WebGPU runtime + model fetch as soon as a file is
// picked, so it overlaps with the user reviewing their photo instead of
// blocking "Start". removeBackground() re-uses this same (memoized) load.
function warmUp() {
    (async () => {
        const { preload } = await loadImglyModule();
        const config = await getSharedConfig();
        await preload(config);
    })().catch((err) => {
        console.warn("Warm-up preload failed (will retry on Start):", err);
    });
}

async function runRemoveBackground(file) {
    const { removeBackground } = await loadImglyModule();
    const config = await getSharedConfig();
    try {
        return await removeBackground(file, config);
    } catch (err) {
        if (config.device !== "gpu") throw err;
        console.warn("GPU background removal failed, falling back to CPU.", err);
        sharedConfig = { ...config, device: "cpu" };
        return await removeBackground(file, sharedConfig);
    }
}

async function handleStart() {
    if (!currentFile) return;

    clearError();
    progressFill.style.width = "0%";
    progressText.textContent = "Loading model…";
    showStage("processing");
    setStep(3);

    try {
        const blob = await runRemoveBackground(currentFile);

        if (resultUrl) URL.revokeObjectURL(resultUrl);
        resultUrl = URL.createObjectURL(blob);
        resultImg.src = resultUrl;
        downloadBtn.href = resultUrl;

        showStage("result");
        setStep(4);
    } catch (err) {
        console.error(err);
        startBtn.disabled = false;
        showStage("upload");
        setStep(2);
        const detail = err && (err.message || String(err));
        showError(
            "Something went wrong while removing the background"
            + (detail ? ": " + detail : "")
            + ". Check your internet connection and try again."
        );
    }
}

dropzone.addEventListener("click", () => fileInput.click());

dropzone.addEventListener("keydown", (e) => {
    if (e.key === "Enter" || e.key === " ") {
        e.preventDefault();
        fileInput.click();
    }
});

dropzone.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropzone.classList.add("dragover");
});

dropzone.addEventListener("dragleave", () => {
    dropzone.classList.remove("dragover");
});

dropzone.addEventListener("drop", (e) => {
    e.preventDefault();
    dropzone.classList.remove("dragover");
    const file = e.dataTransfer.files && e.dataTransfer.files[0];
    handleFile(file);
});

fileInput.addEventListener("change", () => {
    handleFile(fileInput.files[0]);
});

startBtn.addEventListener("click", handleStart);
resetBtn.addEventListener("click", resetAll);

setStep(1);
