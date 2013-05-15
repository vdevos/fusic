# Fusic

Fusic is a social music network that enables you to collaborate your favorite music with your friends and discover new music from everyone else!

+ Listen to your favorite songs
+ Create your own playlists and follow those of others
+ Let friends collaborate in your playlist by making them editors
+ Love and share your favorite songs and playlists
+ Keep track of your friends and their music activity
+ Follow new playlists and discover new music from other other users

# Kohana

Fusic is build using the Kohana 3.2 PHP Framework

Read this page on the [Documentation](http://kohanaframework.org/3.2/guide/)


# GIT

If you want to contribute, please read this page on [How To Fork A Repo](https://help.github.com/articles/fork-a-repo)

Make sure to test everything you commit in a pull request!

Also make sure you setup your own test database (do not use the __live__ database)

1. Create the database structure using `setup.sql`
2. Edit the database config file `$FUSIC_ROOT/modules/database/config/database.php`

# Functionality

Checkout the __[API](https://github.com/vdevos/fusic/blob/master/API.md)__ for back-end functionality

Each of the following sub-headers describes a view and it's corresponding features. These are features for users that have a account and are logged in. Feautures for users without a account have yet to be specified.

## 1. Explore

The purpose of this view is discovering new music.

__Timeline__ Twitter style with song entries from:
- A new song in a users following playlists
- A loved song from a friend

__Trending__ Small list (daily, weekly, monthly etc.) with:
- Most loved songs
- Most played songs

## 2. Playlists overview

This view shows a overview of playlists that a user __owns__ and __follows__. This view displays the following information for each playlist.

__Owned__
- __Info__ title, creation date, tags, description and (optional) image
- __Lock__ Public (for friends and other) or Private (only yourself)
- __Stats__ total songs, play duration, total plays, followers and loves
- __Actions__
    - Update: title, tags, description, image, lock
    - Remove playlist

__Following__ 

- __Info__ title, creation date, tags, description and (optional) image
- __Follow__ Following or not following
- __Privilege__ Viewer or Editor
- __Stats__ total songs, play duration, total plays, followers and loves
- __Actions__
    - Follow or unfollow playlist

## 3. Playlist 

This view shows the contents of a playlist and embeds the Fusic Player for playing the actual music

__FusicPlayer__
- Play/Pause
- Next/Previous
- Shuffle & Loop

__Info__ about playlist
- Title
- Cover
- Owner
- Created

__Songs__ in playlist
- Title + Url
- Duration
- Added by user
- Added on date
- Active listeners 
- __Actions__
    - Add song
    - Remove song
    - Love song

__Followers__ in playlist
- Username
- Active song
- Privilege: Viewer/Editor

__Stats__ from playlist
- Total songs
- Total duration
- Total plays
- Total followers
- Total loved songs
- History of songs played
    - song
    - user
    - date

## 4. Loved songs

The is a playlist view with a list of a users loved songs (see: Playlist)

## 5. Profile

A view of a users profile

- User information
- Friend or not
- List of owning playlists
- List of following playlists
- List of friends
