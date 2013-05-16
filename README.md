# Fusic

Fusic should be a social music network that enables you to collaborate your favorite music with your friends and discover new music from everyone else!

These items below should be the main features of Fusic version 1.0
+ Listen to your favorite songs
+ Create your own playlists and follow those of others
+ Let friends collaborate in your playlist by making them editors
+ Love and share your favorite songs and playlists
+ Keep track of your friends and their music activity
+ Follow new playlists and discover new music from other other users

For a more detailed description see __Functionality__ below.

# Information

__Kohana__

Fusic is build using the Kohana 3.2 PHP Framework - Read the [Documentation](http://kohanaframework.org/3.2/guide/)

__GIT__

If you want to contribute, please read this page on [How To Fork A Repo](https://help.github.com/articles/fork-a-repo)

__WARNING__ NEVER PUSH TO THE MASTER BRANCH!

Make sure to test everything before you commit and create a pull request!

1. Clone the repo `git clone git@github.com:vdevos/fusic.git fusic`
2. Create a new development branch named <user>.<feature> `git branch vdevos.newfeature`
3. Switch to the new branch `git checkout vdevos.newfeature`
4. Do some `echo "coding";` and make Fusic more awesome!
5. Add your changes to the branch `git add .` and commit them `git commit -m "Some info about the feature etc."`
6. Push the changes in the branch to the Fusic repo `git push -u origin master`
7. Go the github and make a `Pull Request` from branch `<user>.<feature>` to `master`


__Database__

__Never__ use the __live__ database, but setup your own database instead!

1. Create the initial database structure using `setup.sql`
2. Adjust the database config file `$FUSIC_ROOT/modules/database/config/database.php`

Add a new `else if` statement with your domain `fusic.website.nl` bijvoorbeeld.

    else if(isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'fusic.website.nl')
    {
        return array
        (
            'default' => array
            (
                'type'       => 'mysql',
                'connection' => array(
                     'hostname'   => 'localhost',
                     'database'   => 'your_db_name',
                     'username'   => 'your_db_username',
                     'password'   => 'your_db_user_password',
                     'persistent' => FALSE,
                ),
                'table_prefix' => '',
                'charset'      => 'utf8',
                'caching'      => FALSE,
                'profiling'    => TRUE,
            ),
        );
    }



# Functionality

Each of the following sub-headers describes a view and it's corresponding features. These are features for users that have a account and are logged in. Feautures for users without a account have yet to be specified.

## 1. Explore - `/explore/`

The purpose of this view is discovering new music:

__Timeline__ Twitter style - list of song entries derived from:
- New songs in playlists that are being followed
- Loved song from friends

__Trending__ Twitter style - small list of song entries (daily, weekly, monthly etc.) with:
- Most loved songs
- Most played songs
- Most added songs

## 2. Playlists overview - `/playlists/`

This view shows an overview of playlists that a user __owns__ and __follows__. This view displays the following information for each playlist.

__Owned__ -
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

## 3. Playlist - `/playlist/show/<id>`

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

## 4. Loved songs - `/loved/`

The is a playlist view with a list of a users loved songs (see: Playlist)

## 5. Friends - `/friends/`

This view displays all a users friends and some information about them.

__Information__ for each friend
- Username
- Cover
- Total owning playlists
- Total following playlists
- Total friends

## 6. Profile - `/user/<username>` 

A view of a users profile

- User information
- Friend or not
- List of owning playlists
- List of following playlists
- List of friends
