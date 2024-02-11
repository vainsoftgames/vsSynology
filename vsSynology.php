<?php

class vsSynology {
    // @var string Host of the Synology Chat server
    public $host;
    // @var int Port number for the Synology Chat server
    public $port;
    // @var array Cached list of users
    public $users;
    // @var string Authentication token for API access
    public $token;

    /**
     * Constructor: Initializes the Synology Chat API client.
     */
    public function __construct(){
        // Initialization code can be added here if needed
    }

    /**
     * Generates the full URL to the Synology Chat API.
     * 
     * @return string The full API URL.
     */
    private function genURL(){
        return ($this->host .':'. $this->port .'/webapi/entry.cgi');
    }

    /**
     * Configures common cURL options for SSL and authorization headers.
     * 
     * @param resource $ch The cURL handle.
     * @param bool $headers Whether to include authorization headers.
     */
    private function curlSetup($ch, $headers=true){
        if($headers){
            $headers = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token
            ];
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Note: Security risk in production
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); // Note: Security risk in production
    }

    /**
     * Executes a cURL request with provided parameters and extracts a specific part of the response.
     * 
     * @param array $params The parameters for the API request.
     * @param string $key The key of the data to retrieve from the response.
     * 
     * @return mixed The extracted data or null if not found/error.
     */
    private function curlPayload($params, $key){
        $queryString = http_build_query($params);
        $ch = curl_init("{$this->genURL()}?{$queryString}");
        $this->curlSetup($ch);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);
        return $data['data'][$key] ?? null;
    }

    /**
     * Checks if a user exists within the cached user list.
     * 
     * @param int $userID The user ID to check.
     * 
     * @return bool True if the user exists, false otherwise.
     */
    public function userExists($userID){
        if(!$this->users){
            $this->getUsers();
        }

        foreach($this->users as $v){
            if($v['user_id'] == $userID) return true;
        }

        return false;
    }

    /**
     * Retrieves the username or nickname for a given user ID.
     * 
     * @param int $userID The user ID.
     * 
     * @return string|false The username or nickname if found, false otherwise.
     */
    public function getUserName($userID){
        if(!$this->users){
            $this->getUsers();
        }

        foreach($this->users as $v){
            if($v['user_id'] == $userID){
                return isset($v['nickname']) && $v['nickname'] != '' ? $v['nickname'] : $v['username'];
            }
        }
        return false;
    }

    /**
     * Retrieves and caches the list of users from Synology Chat.
     * 
     * @param bool $cID Optional. If provided, filters users by channel ID (if API supports it).
     * 
     * @return array The list of users.
     */
    public function getUsers($cID=false) {
        $params = [
            'api' => 'SYNO.Chat.External',
            'method' => 'user_list',
            'version' => '2',
            'token' => $this->token,
        ];

        return $this->users = $this->curlPayload($params, 'users');
    }

    /**
     * Sends a message to a specified channel or user.
     * 
     * @param string $message The message text to send.
     * @param int $cID The channel ID where the message will be sent.
     * 
     * @return mixed The response from the API.
     */
    public function sendMessage($message, $cID) {
        $params = [
            'api' => 'SYNO.Chat.External',
            'method' => 'chatbot', // Adjust according to correct API documentation
            'version' => '2',
            'text' => $message,
            'channel_id' => $cID,
            'token' => $this->token,
        ];

        return $this->curlPayload($params, 'posts');
    }

    /**
     * Retrieves a list of conversations available to the bot.
     * 
     * @return array The list of channels/conversations.
     */
    public function getConversations(){
        $params = [
            'api' => 'SYNO.Chat.External',
            'method' => 'channel_list',
            'version' => '2',
            'token' => $this->token,
        ];

        return $this->curlPayload($params, 'channels');
    }

    /**
     * Retrieves messages from a specific thread or channel.
     * 
     * @param int $cID The channel ID.
     * @param int|null $postId Optional. The post ID to start retrieving messages from.
     * 
     * @return array The list of messages.
     */
    public function getThreadMessages($cID, $postId = null) {
        $params = [
            'api' => 'SYNO.Chat.External',
            'method' => 'post_list',
            'version' => '2',
            'token' => $this->token,
            'channel_id' => $cID,
            'next_count' => 100,
            'prev_count' => 1,
        ];

        return $this->curlPayload($params, 'posts');
    }

    /**
     * Attempts to retrieve a list of bots. (Note: May not work if not supported by API)
     * 
     * @return array|null The list of bots or null if not supported/error.
     */
    public function getBots(){
        $params = [
            'api' => 'SYNO.Chat.External',
            'method' => 'bot_list',
            'version' => '1',
            'token' => $this->token,
        ];

        return $this->curlPayload($params, 'bots');
    }
}

?>
