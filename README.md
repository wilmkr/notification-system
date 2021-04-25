# Notification System
This is a simlple demonstration of a publish/subscribe notification system in Laravel. I keep track of topics and subscribers. When a message is published on a topic, all subscribers to that topic get notified almost instantaneously. 

## Key tools/technologies used
+ Laravel 8.1
+ Websockets (pusher PHP server)
+ Events
+ Event listeners
+ Broadcasting

## Installation

There are different ways to execute this project, I recommend using Laravel sail, a light-weight command-line interface for interacting with Laravel's default Docker development environment. Laravel Sail is supported on macOS, Linux, and Windows. 

To get started:

+ Make sure you have [Composer](https://getcomposer.org/doc/00-intro.md) installed on your computer to manage your dependencies.
+ Download and install [Docker Desktop](https://www.docker.com/)  
+ Clone this repo
+ Cd in the application folder then [setup laravel sail](https://laravel.com/docs/8.x/sail) for the application. Once laravel sail is setup, you can then run `sail up` command to start up your docker containers. 

```
cd notification-system

composer require laravel/sail --dev

./vendor/bin/sail up -d
`````


The first time you run the `sail up` command, Sail's application containers will be built on your machine. This could take several minutes. Subsequent attempts to start Sail will be much faster. Once the application's containers have been started, install the project's dependencies.

```
sail composer install

`````

__Database__

The application's `docker-compose.yml` file contains an entry for a MySQL container. This container uses a Docker volume so that the data stored in your database is persisted even when stopping and restarting your containers. In addition, when the MySQL container is starting, it will ensure a database exists whose name matches the value of your `DB_DATABASE` environment variable.

Once you have started your containers, you may connect to the MySQL instance using a database client by setting your `DB_HOST` environment variable within your application's `.env` file to `mysql`.

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE={database name}
DB_USERNAME={username}
DB_PASSWORD={password}
`````

After connecting to the database, run the application's database migrations to create the required database tables. You should also seed the topics table.

```
sail artisan migrate

sail artisan db:seed --class=TopicSeeder
`````

You may interact with the project's endpoints via [Postman](https://www.postman.com/downloads/) or similar tools.

__Endpoints__
```
POST /api/subscribe/{topicId}
Data:
{
	"url" : "http://mysubscriber.test"
}

Response:
{
    "success": true,
    "status_code": 200,
    "message": "Subscribed",
    "data": {
        "url": "http://mysubscriber.test",
        "topic": "topic_3"
    }
}

POST /api/publish/{topicId}
Data:
{
	"message" : "hello world!"
}

Response:
{
    "success": true,
    "status_code": 200,
    "message": "Message published",
    "data": {
        "message": "foo bar",
        "topic": "topic_1"
    }
}
`````


__PHP Pusher__
Pusher is a hosted API service which makes adding real-time data and functionality to web and mobile applications seamless. Pusher works as a real-time communication layer between the server and the client. It maintains persistent connections at the client using WebSockets, as and when new data is added to your server.

To run this project, you'll need to sign up with [pusher.com](https://pusher.com/) to create an account. It only takes two minutes. On your account dashboard create an app under channels. You'll find the follwing values generated for you which you should then add to your `.env` file.

```
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=

`````

## Usage

Once pusher is set up, you will need to set up client's to establish a websocket connection to the app. The client(s) are the subsccribers who will get notified whenever messages get published to topics.

__Clients__
For the client, I use the pusher.js package to subscribe to topics. Another great alternative I could have used is laravel echo which is a tool that makes it easy to subscribe to channels and listen for events on the channels.

On the client, using pusher.js, I tell the client which channel (topic) to subscribe to, and what event to listen for. So for example a client can subscribe to topic_1 and listen for a MessagePublished event. For this use case, when the client receives published data on the channel from the event, the data is simply logged out to the console.

The snippet below connects to Channels and subscribes to a channel called topic_1, listening for the MessagePublished event. Create the index.html file on your system and paste the content below into it to test. Replace PUSHER_APP_ID and PUSHER_APP_CLUSTER with the actual values from your pusher account dashboard.


```
<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('{PUSHER_APP_ID}', {
      cluster: '{PUSHER_APP_CLUSTER}'
    });

    var channel = pusher.subscribe('topic_1');
    channel.bind('MessagePublished', function(data) {
      alert(JSON.stringify(data));
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>my-channel</code>
    with event name <code>my-event</code>.
  </p>
</body>

`````

In your browser, open up the console via your browser's element inspector to view the data being pushed to the client from the websocket server. Use the API "publish" endpoint to publish a message to the websocket server. 

__Unit tests__
I left these out on purpose due to time constraints. I will include these later. Some of the things I would test for include:

+ Test if message publishing event is being triggered
+ Test if message is actually published to channel when event is triggered
+ Test if event listener is called when event is triggered and that the listener is actually handling the event

