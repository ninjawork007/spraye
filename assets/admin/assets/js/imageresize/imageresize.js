const dataURItoBlob = (dataURI) => {
  const bytes = dataURI.split(',')[0].indexOf('base64') >= 0
    ? atob(dataURI.split(',')[1])
    : unescape(dataURI.split(',')[1]);
  const mime = dataURI.split(',')[0].split(':')[1].split(';')[0];
  const max = bytes.length;
  const ia = new Uint8Array(max);
  for (let i = 0; i < max; i += 1) ia[i] = bytes.charCodeAt(i);
  return new Blob([ia], { type: mime });
};

const resizeImage = ( {file, maxSize} ) => {  
  console.log('resizeimage');
  console.log(file);  
  const reader = new FileReader();
  const image = new Image();
  const canvas = document.createElement('canvas');
  const resize = () => {
    console.log('in resize');
    let { width, height } = image;
    if (width > height) {
      if (width > maxSize) {
        height *= maxSize / width;
        width = maxSize;
      }
    } else if (height > maxSize) {
      width *= maxSize / height;
      height = maxSize;
    }
    canvas.width = width;
    canvas.height = height;
    canvas.getContext('2d').drawImage(image, 0, 0, width, height);
    const dataUrl = canvas.toDataURL('image/jpg', 0.8);
    console.log(dataUrl);
    document.getElementById('resized_image').value = dataUrl;    
    return dataURItoBlob(dataUrl);
  };
  console.log('before promise');
  return new Promise((ok, no) => {
    if (!file.type.match(/image.*/)) {
      no(new Error('Not an image'));
      return;
    }

    reader.onload = (readerEvent) => {
      console.log('in onload');
      image.onload = () => ok(resize());
      image.src = readerEvent.target.result;      
      
    };
    console.log('before readAsDataURL');
    reader.readAsDataURL(file);
  });

};