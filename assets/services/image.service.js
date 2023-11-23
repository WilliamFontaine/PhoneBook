class ImageService {
  handleFile(file, image_name) {
    const blob = new Blob([file]);
    const imageUrl = URL.createObjectURL(blob);
    ;

    return imageUrl;
  }
}

export default new ImageService();
