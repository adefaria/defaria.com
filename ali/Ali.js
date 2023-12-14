const player = document.getElementById("player");
const links = document.querySelectorAll("a[data-song-url]");
const title = document.getElementById("songTitle");
const story = document.getElementById("story");
const songsList = document.getElementById("songs");
const songs = Array.from(songsList.querySelectorAll("a"));
// const songSelection = document.getElementById("songSelection");

nextSong = 0;

player.addEventListener("ended", playNext);

function displayStoryAndPlay(songUrl) {
  // Format songName
  extensionIndex = songUrl.lastIndexOf(".");
  songName = songUrl.substring(6, extensionIndex);
  title.innerHTML = songName;

  // Set story for song - look for songName.html
  const songsStory = "Stories/" + songName + ".html";

  // Change the iFrame
  story.src = songsStory;

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
