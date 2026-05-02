class PluginLazyVideo {
  constructor(videoUrl, options = {}) {
    let defaultOptions = {
      isFile: false,
    };

    this.options = Object.assign(defaultOptions, options);
    this.isFile = options.isFile;
    this.videoUrl = videoUrl;
    this.container = options.container;

    if (this.container) {
      this.thumbnail = this.container.querySelector(".video__thumbnail");
      this.playButton = this.container.querySelector(".video__play");
    } else {
      console.error("Ошибка: Не найден блок .video");
      return;
    }

    this.check();
    this.init();
  }

  check() {
    if (!this.videoUrl) {
      console.error("Ошибка: Не указан адрес видео");
      return;
    }

    if (!this.playButton) {
      console.error("Ошибка: Не найдена кнопка воспроизведения");
      return;
    }
  }

  init() {
    this.playButton?.addEventListener("click", () => this.loadVideo());
  }

  loadVideo() {
    this.thumbnail.remove();
    this.playButton.remove();

    if (this.isFile) {
      const video = document.createElement("video");
      video.src = this.videoUrl;
      video.controls = true;
      video.autoplay = true;
      this.container.appendChild(video);
    } else {
      const iframe = document.createElement("iframe");
      iframe.src = `${this.videoUrl}?autoplay=1`;
      iframe.allow = "autoplay; encrypted-media";
      iframe.allowFullscreen = true;
      this.container.appendChild(iframe);
    }
  }
}

export { PluginLazyVideo };
export default PluginLazyVideo;
