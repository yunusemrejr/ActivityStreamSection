# ActivityStreamSection
Activity Stream page within my personal website yunusemrevurgun.com

#About the app
Activity Stream is a section of my personal website yunusemrevurgun.com and it is accessible through https://yunusemrevurgun.com/stream/index.php
You can think of it like a Twitter feed. There is an admin panel to create and delete posts.
The application makes use of PHP as a backend language and MySQL to store data.
Each log has these properties: date, time, title, body.
Date and time are automatically retrieved from the server at the time of posting. Title and body are inputted from the admin panel.
On the index page, the most recent post is at the top and pagination has been applied. 
Security measures are not advanced but the administrator password is stored as hash within the PHP file. Admin password must be hashed beforehand with a 3rd party software.
I would not recommend using this app as it is for an enterprise solution as I did not complete the full security measurements.
During the development of this app, I made use of chat.openai.com, more specifically the language model GPT 3.5 for exploring methods to construct the application and to get example code snippets, discuss the situation and ask questions.
