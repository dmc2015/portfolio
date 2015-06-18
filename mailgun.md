# First, instantiate the SDK with your API credentials and define your domain.
$mg = new Mailgun("key-example");
$domain = "example.com";

# Now, compose and send your message.
$mg->sendMessage($domain, array('from'    => 'bob@example.com',
                                'to'      => 'sally@example.com',
                                'subject' => 'The PHP SDK is awesome!',
                                'text'    => 'It is so simple to send a message.'));
