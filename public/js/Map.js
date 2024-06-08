document.addEventListener("DOMContentLoaded", function () {
  const canvas = document.getElementById("drawingCanvas");
  const map = document.getElementById("map");

  // Set canvas size to match map size
  canvas.width = map.clientWidth;
  canvas.height = map.clientHeight;

  const ctx = canvas.getContext("2d");
  let drawingColor = "black";
  let drawingWidth = 3;
  let isDrawing = false;
  let lastX = 0;
  let lastY = 0;

  function getTouchPos(canvasDom, touchEvent) {
    const rect = canvasDom.getBoundingClientRect();
    return {
      x: touchEvent.touches[0].clientX - rect.left,
      y: touchEvent.touches[0].clientY - rect.top,
    };
  }

  function draw(e) {
    if (!isDrawing) return;

    let x, y;
    if (e.type.startsWith("touch")) {
      e.preventDefault();
      const touchPos = getTouchPos(canvas, e);
      x = touchPos.x;
      y = touchPos.y;
    } else {
      x = e.offsetX;
      y = e.offsetY;
    }

    ctx.strokeStyle = drawingColor;
    ctx.lineWidth = drawingWidth;
    ctx.beginPath();
    ctx.moveTo(lastX, lastY);
    ctx.lineTo(x, y);
    ctx.stroke();
    [lastX, lastY] = [x, y];
  }

  function changeColor(color) {
    drawingColor = color;
  }

  function changeWidth(width) {
    drawingWidth = width;
  }

  function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
  }

  canvas.addEventListener("mousedown", (e) => {
    isDrawing = true;
    [lastX, lastY] = [e.offsetX, e.offsetY];
  });

  canvas.addEventListener("mousemove", draw);
  canvas.addEventListener("mouseup", () => (isDrawing = false));
  canvas.addEventListener("mouseout", () => (isDrawing = false));

  // Handle touch events
  canvas.addEventListener("touchstart", (e) => {
    isDrawing = true;
    const touchPos = getTouchPos(canvas, e);
    [lastX, lastY] = [touchPos.x, touchPos.y];
  });

  canvas.addEventListener("touchmove", draw);
  canvas.addEventListener("touchend", () => (isDrawing = false));
  canvas.addEventListener("touchcancel", () => (isDrawing = false));

  // Event listeners for width buttons
  document
    .getElementById("thinWidthBtn")
    .addEventListener("click", () => changeWidth(3));
  document
    .getElementById("mediumWidthBtn")
    .addEventListener("click", () => changeWidth(6));
  document
    .getElementById("thickWidthBtn")
    .addEventListener("click", () => changeWidth(9));

  // Event listener for clear button
  document.getElementById("clearBtn").addEventListener("click", clearCanvas);

  // Event listener for color picker
  document
    .getElementById("colorPicker")
    .addEventListener("input", (e) => changeColor(e.target.value));

  // Resize canvas on window resize
  window.addEventListener("resize", () => {
    const prevImage = new Image();
    prevImage.src = canvas.toDataURL();

    canvas.width = map.clientWidth;
    canvas.height = map.clientHeight;

    prevImage.onload = () => {
      ctx.drawImage(prevImage, 0, 0);
    };
  });
});
