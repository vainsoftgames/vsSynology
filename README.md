# vsSynology
Interact with Synology Chat via API using PHP.
<br><i>This is an “unofficial" Synology Chat API library that I maintain.</i>
<!DOCTYPE html>
<html lang="en">
<body>
    <article>
        <header>
            <h1>vsSynology Class Usage Guide</h1>
        </header>
        <section>
            <h2>Introduction</h2>
            <p>The <code>vsSynology</code> class is designed to interact with Synology Chat Server's API, enabling developers to automate tasks such as sending messages, listing users, and retrieving conversations. This class simplifies the process of making API requests to the Synology Chat Server by abstracting away the complexity of direct API calls.</p>
        </section>
        <section>
            <h2>Features</h2>
            <ul>
                <li>Send messages to channels or users.</li>
                <li>Retrieve a list of users and their details.</li>
                <li>Check if a user exists.</li>
                <li>Get a list of conversations available to the bot.</li>
                <li>Fetch messages from a specific thread or channel.</li>
            </ul>
        </section>
        <section>
            <h2>Prerequisites</h2>
            <p>To use the <code>vsSynology</code> class, you need:</p>
            <ul>
                <li>A Synology Chat Server setup with API access enabled.</li>
                <li>An API token for authenticating your requests.</li>
                <li>The host and port of your Synology Chat Server.</li>
            </ul>
        </section>
        <section>
            <h2>Setup and Initialization</h2>
            <p>First, include the <code>vsSynology</code> class in your project. Then, initialize the class with your Synology Chat Server's details:</p>
            <pre><code>&lt;?php
require 'path/to/vsSynology.php';

$chat = new vsSynology();
$chat-&gt;host = 'your_chat_server_host';
$chat-&gt;port = your_chat_server_port;
$chat-&gt;token = 'your_api_token';
?&gt;
            </code></pre>
        </section>
        <section>
            <h2>Usage Examples</h2>
            <h3>Sending a Message (WIP)</h3>
            <pre><code>&lt;?php
$channelId = 123; // The ID of the channel where the message will be sent
$message = 'Hello, Synology Chat!';
$chat-&gt;sendMessage($message, $channelId);
?&gt;
            </code></pre>
            <h3>Retrieving Users</h3>
            <pre><code>&lt;?php
$users = $chat-&gt;getUsers();
print_r($users);
?&gt;
            </code></pre>
            <h3>Get list of conversations available to bot</h3>
            <pre><code>&lt;?php
$conv = $chat-&gt;getConversations();
print_r($conv);
?&gt;
            </code></pre>
            <h3>Get Thread Messages</h3>
            <pre><code>&lt;?php
$channelId = 123; // The ID of the channel where the message will be sent
$conv = $chat-&gt;getThreadMessages($channelId);
print_r($conv);
?&gt;
            </code></pre>
            <h3>WebHook Response: INCOMING</h3>
            <pre><code>&lt;?php
            echo json_encode(['text'=>'Message to send back to user']);
            ?>
            </code></pre>
            <pre><code>&lt;?php
            echo json_encode(['text'=>'Message to send back to user', 'file_url'=>'URL to file attachment']);
            ?>
            </code></pre>
        </section>
        <footer>
            <h2>Additional Notes</h2>
            <p>Ensure that you have the necessary permissions to perform actions with the API. For detailed API documentation and more complex functionalities, refer to the official Synology Chat API documentation.</p>
        </footer>
    </article>
</body>
</html>
