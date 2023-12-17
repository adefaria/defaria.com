const player = document.getElementById("player");
const links = document.querySelectorAll("a[data-song-url]");
const story = document.getElementById("story");
const songsList = document.getElementById("songs");
const songs = Array.from(songsList.querySelectorAll("a"));
const images = [
  { url: "Images/Ali1.jpg" },
  { url: "Images/Ali2.jpg" },
  { url: "Images/Ali3.jpg" },
  { url: "Images/Ali4.jpg" },
  { url: "Images/Ali5.jpg" },
  { url: "Images/Ali6.jpg" },
  { url: "Images/Ali7.jpg" },
];

nextSong = 0;

player.addEventListener("ended", playNext);

function setImages() {
  const randomIndexLeft = Math.floor(Math.random() * images.length);

  randomIndexRight = randomIndexLeft;

  // Don't want two of the same pics so loop until we find a different one
  while (randomIndexLeft == randomIndexRight) {
    randomIndexRight = Math.floor(Math.random() * images.length);
  }

  const selectedImageLeft = images[randomIndexLeft];
  const selectedImageRight = images[randomIndexRight];

  const leftPic = document.getElementById("leftImage");
  const rightPic = document.getElementById("rightImage");

  if (leftPic != null) {
    leftPic.src = selectedImageLeft.url;
  }
  if (rightPic != null) {
    rightPic.src = selectedImageRight.url;
  }
}

function displayStoryAndPlay(songUrl) {
  // Format songName
  extensionIndex = songUrl.lastIndexOf(".");
  songName = songUrl.substring(6, extensionIndex);

  // Set story for song - look for songName.html
  const songsStory = "Stories/" + songName + ".html";

  // Change the iFrame
  story.src = songsStory;

  // Change images
  setImages();

  player.src = songUrl;
  player.play();
}

function playNext() {
  if (!songs.length) return; // No more songs to play

  for (let i = nextSong; i < songs.length; i++) {
    songUrl = songs[i].getAttribute("data-song-url");

    nextSong = i + 1;

    displayStoryAndPlay(songUrl);

    // Break after song starts. We'll be called back when "ended" event strikes.
    break;
  }
}

// This starts playback
playNext();

links.forEach((link) => {
  link.addEventListener("click", (event) => {
    // Do not follow link - we're just loading the song
    event.preventDefault();

    // Get the song's URL
    //songUrl = link.dataset.songUrl;
    songUrl = link.dataset["songUrl"];
    nextSong = parseInt(link.dataset["index"]) + 1;

    displayStoryAndPlay(songUrl);
  });
});
