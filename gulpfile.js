const { src, dest, series, parallel, watch } = require("gulp");
const gulpif = require("gulp-if");
const plumber = require("gulp-plumber");
const notify = require("gulp-notify");
const sourcemaps = require("gulp-sourcemaps");
const sass = require("sass");
const gulpSass = require("gulp-sass");
const autoprefixer = require("gulp-autoprefixer");
const cleanCSS = require("gulp-clean-css");
const webpackStream = require("webpack-stream");
const uglify = require("gulp-uglify");
const svgSprite = require("gulp-svg-sprite");
const svgmin = require("gulp-svgmin");
const cheerio = require("gulp-cheerio");
const replace = require("gulp-replace");
const imagemin = require("gulp-imagemin");
const webp = require("gulp-webp");
const newer = require("gulp-newer");
const del = require("del");
const browserSync = require("browser-sync").create();

const mainSass = gulpSass(sass);

// Режим сборки
let isProd = false;
let isBackend = false;

// Пути к файлам (ОБНОВЛЕНО)
const paths = {
  // SCSS
  scss: {
    src: "./scss/**/*.scss",
    main: "./scss/main.scss",
    vendor: "./scss/vendor.scss",
    dest: "./css/",
  },

  // JS - теперь исходники в js-src, результат в js
  js: {
    src: "./js-src/main.js", // Исходники из папки js-src
    components: "./js-src/components/**/*.js", // Компоненты там же
    vendor: "./js-src/vendor/**/*.js",
    dest: "./js/", // Результат в папку js
    outputName: "main.min.js", // Имя выходного файла
  },

  // SVG
  svg: {
    src: "./img/svg/*.svg",
    dest: "./img/",
  },

  // Изображения
  images: {
    src: "./img/**/*.{jpg,jpeg,png,gif}",
    dest: "./img/",
  },

  // PHP (для BrowserSync)
  php: {
    src: "./**/*.php",
  },
};

// Очистка - удаляем только результат сборки (НЕ исходники)
const clean = () => {
  return del([
    "./css/*.css",
    "./css/*.css.map",
    "./js/main.min.js", // Только результат сборки
    "./js/main.min.js.map", // Только результат сборки
    "./img/sprite.svg",
  ]);
};

// SVG спрайт
const svgSprites = () => {
  return src(paths.svg.src)
    .pipe(
      plumber(
        notify.onError({
          title: "SVG Error",
          message: "Error: <%= error.message %>",
        }),
      ),
    )
    .pipe(
      svgmin({
        js2svg: {
          pretty: true,
        },
      }),
    )
    .pipe(
      cheerio({
        run: function ($) {
          $("[fill]").removeAttr("fill");
          $("[stroke]").removeAttr("stroke");
          $("[style]").removeAttr("style");
        },
        parserOptions: {
          xmlMode: true,
        },
      }),
    )
    .pipe(replace("&gt;", ">"))
    .pipe(
      svgSprite({
        mode: {
          symbol: {
            sprite: "sprite.svg",
            dest: ".",
          },
        },
      }),
    )
    .pipe(dest(paths.svg.dest))
    .pipe(browserSync.stream());
};

// Компиляция SCSS (DEV режим)
const styles = () => {
  return src([paths.scss.vendor, paths.scss.main])
    .pipe(
      plumber(
        notify.onError({
          title: "SCSS Error",
          message: "Error: <%= error.message %>",
        }),
      ),
    )
    .pipe(gulpif(!isProd && !isBackend, sourcemaps.init()))
    .pipe(mainSass().on("error", mainSass.logError))
    .pipe(
      autoprefixer({
        cascade: false,
        grid: true,
        overrideBrowserslist: ["last 5 versions"],
      }),
    )
    .pipe(gulpif(isProd, cleanCSS({ level: 2 })))
    .pipe(gulpif(!isProd && !isBackend, sourcemaps.write(".")))
    .pipe(dest(paths.scss.dest))
    .pipe(browserSync.stream());
};

// Компиляция SCSS (BACKEND режим - без sourcemaps)
const stylesBackend = () => {
  return src([paths.scss.vendor, paths.scss.main])
    .pipe(
      plumber(
        notify.onError({
          title: "SCSS Error",
          message: "Error: <%= error.message %>",
        }),
      ),
    )
    .pipe(mainSass().on("error", mainSass.logError))
    .pipe(
      autoprefixer({
        cascade: false,
        grid: true,
        overrideBrowserslist: ["last 5 versions"],
      }),
    )
    .pipe(dest(paths.scss.dest))
    .pipe(browserSync.stream());
};

// Сборка JS (WEBPACK + BABEL) - читаем из js-src, пишем в js
const scripts = () => {
  return src(paths.js.src)
    .pipe(
      plumber(
        notify.onError({
          title: "JS Error",
          message: "Error: <%= error.message %>",
        }),
      ),
    )
    .pipe(gulpif(!isProd && !isBackend, sourcemaps.init()))
    .pipe(
      webpackStream({
        mode: isProd ? "production" : "development",
        output: {
          filename: paths.js.outputName, // main.min.js
        },
        module: {
          rules: [
            {
              test: /\.m?js$/,
              exclude: /node_modules/,
              use: {
                loader: "babel-loader",
                options: {
                  presets: [
                    [
                      "@babel/preset-env",
                      {
                        targets: "defaults",
                      },
                    ],
                  ],
                },
              },
            },
          ],
        },
        devtool: !isProd && !isBackend ? "source-map" : false,
      }),
    )
    .on("error", function (err) {
      console.error("WEBPACK ERROR", err);
      this.emit("end");
    })
    .pipe(gulpif(isProd, uglify()))
    .pipe(gulpif(!isProd && !isBackend, sourcemaps.write(".")))
    .pipe(dest(paths.js.dest)) // Сохраняем в папку js
    .pipe(browserSync.stream());
};

// Сборка JS (BACKEND режим)
const scriptsBackend = () => {
  return src(paths.js.src)
    .pipe(
      plumber(
        notify.onError({
          title: "JS Error",
          message: "Error: <%= error.message %>",
        }),
      ),
    )
    .pipe(
      webpackStream({
        mode: "development",
        output: {
          filename: paths.js.outputName, // main.min.js
        },
        module: {
          rules: [
            {
              test: /\.m?js$/,
              exclude: /node_modules/,
              use: {
                loader: "babel-loader",
                options: {
                  presets: [
                    [
                      "@babel/preset-env",
                      {
                        targets: "defaults",
                      },
                    ],
                  ],
                },
              },
            },
          ],
        },
        devtool: false,
      }),
    )
    .on("error", function (err) {
      console.error("WEBPACK ERROR", err);
      this.emit("end");
    })
    .pipe(dest(paths.js.dest))
    .pipe(browserSync.stream());
};

// Оптимизация изображений
const images = () => {
  return src(paths.images.src)
    .pipe(newer(paths.images.dest))
    .pipe(gulpif(isProd, imagemin([imagemin.mozjpeg({ quality: 80, progressive: true }), imagemin.optipng({ optimizationLevel: 2 })])))
    .pipe(dest(paths.images.dest));
};

// Конвертация в WebP
const webpImages = () => {
  return src(paths.images.src).pipe(newer(paths.images.dest)).pipe(webp()).pipe(dest(paths.images.dest));
};

// Режим разработки с BrowserSync
const watchFiles = () => {
  // Настройка BrowserSync для WordPress
  browserSync.init({
    proxy: "http://konkord.loc", // ИЗМЕНИТЕ НА ВАШ ЛОКАЛЬНЫЙ URL
    notify: false,
    open: false,
    injectChanges: true,
  });

  // Следим за изменениями в js-src
  watch(paths.scss.src, styles);
  watch([paths.js.src, paths.js.components], scripts); // Теперь следим за js-src
  watch(paths.svg.src, svgSprites);
  watch(paths.images.src, series(images, webpImages));
  watch(paths.php.src).on("change", browserSync.reload);
};

// Режим разработки (DEV) - теперь не удаляем исходники
const dev = series(
  clean, // Очищаем только результат
  parallel(styles, scripts, svgSprites, images, webpImages),
  watchFiles,
);

// Режим для бэкенда (BACKEND)
const backend = series(clean, parallel(stylesBackend, scriptsBackend, svgSprites, images, webpImages));

// Сборка для продакшена (BUILD)
const build = series(
  (cb) => {
    isProd = true;
    cb();
  },
  clean,
  parallel(styles, scripts, svgSprites, images, webpImages),
);

// Экспорт задач
exports.default = dev;
exports.backend = backend;
exports.build = build;
exports.watch = watchFiles;
exports.styles = styles;
exports.scripts = scripts;
exports.svgsprite = svgSprites;
exports.images = images;
exports.webp = webpImages;
