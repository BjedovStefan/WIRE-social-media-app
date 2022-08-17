const cam = document.getElementById("vid")
let signInLabel = document.querySelector('.title')

const startVideo = () => {
  navigator.mediaDevices.getUserMedia({
    video: true
  })
  .then(stream => {
    cam.srcObject = stream;
  })
}


  const loadLabels = () => {
    const labels = ['Bjedov','Siljic'];
    return Promise.all(labels.map(async label => {
        const descriptions = [];
        for (let i = 1; i <= 2; i++) {
            const img = await faceapi.fetchImage(`assets/lib/face-api/labels/${label}/${i}.jpg`)
            const detections = await faceapi
                .detectSingleFace(img).withFaceLandmarks().withFaceDescriptor()

                if(img === null || img === undefined){
                  console.log('nije dobro');
                }else{
                  console.dir(img);
                }

                descriptions.push(detections.descriptor)
        }
        return new faceapi.LabeledFaceDescriptors(label, descriptions)
    }))
} 

Promise.all([
      faceapi.nets.tinyFaceDetector.loadFromUri('assets/lib/face-api/models'),
      faceapi.nets.faceLandmark68Net.loadFromUri('assets/lib/face-api/models'),
      faceapi.nets.faceRecognitionNet.loadFromUri('assets/lib/face-api/models'),
      faceapi.nets.ssdMobilenetv1.loadFromUri('assets/lib/face-api/models')
  ]).then(startVideo)


cam.addEventListener('play', async() => {
  const canvas = faceapi.createCanvasFromMedia(cam);
  const canvasSize = {
    width: cam.width,
    height: cam.height
  }

 const labels = await loadLabels();

  faceapi.matchDimensions(canvas, canvasSize)
  document.body.appendChild(canvas)
  setInterval(async() => {
    const detections = await faceapi.detectAllFaces(
      cam,
      new faceapi.TinyFaceDetectorOptions()
    )
    .withFaceLandmarks()
    .withFaceDescriptors()
    const resizedDetections = faceapi.resizeResults(detections, canvasSize)
    const faceMatcher = new faceapi.FaceMatcher(labels, 0.6)
    const results = resizedDetections.map(d =>{
        console.dir(d); 
        return faceMatcher.findBestMatch(d.descriptor)
    })

    canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height)
    faceapi.draw.drawDetections(canvas, resizedDetections)
    faceapi.draw.drawFaceLandmarks(canvas, resizedDetections)
    results.forEach((result, index) => {
      const box = resizedDetections[index].detection.box
      const { label, distance } = result
      new faceapi.draw.DrawTextField([
        `${label} (${(parseInt(distance * 100, 10))})`
      ], box.bottomRight).draw(canvas)
      if(label !== 'unknown'){
        signInLabel.textContent = `Welcome ${label} to wire!`
     }else{
        signInLabel.textContent = 'Face Recognition Login to WIRE'
     }
    })


  },200)
})
