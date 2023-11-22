class ImageService {
    handleFile(file, image_name) {
        const blob = new Blob([file]);
        const url = URL.createObjectURL(blob);
        const img = document.getElementById(image_name)
        img.src = url;
        img.onload = e => URL.revokeObjectURL(url);

        return blob;
    }
}

export default new ImageService();
