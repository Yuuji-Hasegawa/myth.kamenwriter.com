import fs from 'fs';
import imagemin from 'imagemin';
import imageminMozjpeg from 'imagemin-mozjpeg';
import imageminPngquant from 'imagemin-pngquant';
import imageminGifsicle from 'imagemin-gifsicle';
import imageminSvgo from 'imagemin-svgo';

const getDirRecursively = (dir) => {
  const getChildrenRecursively = (dir) => {
    const readdir = fs.readdirSync(dir, { withFileTypes: true }).filter((d) => d.isDirectory());
    if (readdir.length === 0) {
      return dir;
    } else {
      return readdir.map((p) => getChildrenRecursively(`${dir}/${p.name}`)).flat();
    }
  };
  return [dir, ...getChildrenRecursively(dir)];
};

(async () => {
  const assetsDirs = getDirRecursively('src/img');
  for (let inDir of assetsDirs) {
    const destDir = inDir.replace('src/img', 'dist/img');
    const filesInDir = await imagemin([`${inDir}/*.{jpg,png,gif,svg}`], {
      destination: destDir,
      plugins: [
        imageminMozjpeg({ quality: 85, progressive: true }),
        imageminPngquant({ quality: [0.7, 0.85] }),
        imageminGifsicle({ interlaced: false, optimizationLevel: 10, colors: 256 }),
        imageminSvgo({
          plugins: [
            {
              name: 'removeViewBox',
              active: false,
            },
          ],
        }),
      ],
    });
  }
})();
