// Create a Blob from text
const myBlob = new Blob(["Hello, world!"], { type: "text/plain" });

// Check size and type
console.log(myBlob.size); // 13
console.log(myBlob.type); // "text/plain"
