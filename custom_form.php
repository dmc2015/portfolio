<!-- # Include the Autoloader (see "Libraries" for install instructions)
require 'vendor/autoload.php';
use Mailgun\Mailgun;

# Instantiate the client.
$mgClient = new Mailgun('key-c9c99611081630cde66cb2fa6c4933e2');
$domain = "sandbox392da093ce454bc48350c7a326197b5d.mailgun.org";

# Make the call to the client.
$result = $mgClient->sendMessage("$domain",
array('from'    => 'Mailgun Sandbox <postmaster@sandbox392da093ce454bc48350c7a326197b5d.mailgun.org>',
'to'      => 'don <mclamb.donald@gmail.com>',
'subject' => 'Hello don',
'text'    => 'Congratulations don, you just sent an email with Mailgun!  You are truly awesome!  You can see a record of this email in your logs: https://mailgun.com/cp/log .  You can send up to 300 emails/day from this sandbox server.  Next, you should add your own domain so you can send 10,000 emails/month for free.')); -->


<div class="text-left">
  <form method="post" action="customform_methods.php" class="row">

    <div class="small-6">
      <label for="title" class="inline-label">Title:</label>
      <label class="required">*</label>
      <select name="title">
        <option value="title_option1">Ms.</option>
        <option value="title_option2">Mrs.</option>
        <option value="title_option3">Miss.</option>
        <option value="title_option4">Mr.</option>
      </select>
    </div>

    <div class="small-8">
      <label for="name" class="inline-label">Full Name:</label>
      <label class="required">*</label>
      <input type="text" name="name" id="field_name" placeholder="Full Name">
    </div>

    <div class="small-8">
      <label for="email" class="inline-label">E-Mail:</label>
      <label class="required">*</label>
      <input type="text" name="email" id="field_email" placeholder="E-Mail">
    </div>

    <div class="small-12 text-center">
      <label for="type_of_work" class="inline-label">Contact Reason:</label>
      <label class="required">*</label>
      <input type="checkbox" name="freelance" id="field_type_of_work"><label class="checkboxes" for="freelance">Freelance Work</label></input>
      <input type="checkbox" name="short-term" id="field_type_of_work"><label class="checkboxes" for="short-term">Short-Term</label></input>
      <input type="checkbox" name="long-term" id="field_type_of_work"><label class="checkboxes" for="long-term">Long-Term</label></input>
      <input type="checkbox" name="other" id="field_type_of_work"><label class="checkboxes" for="other">Other</label></input>
    </div>

    <!-- <?php
    //if
    // Other details note
    //<label for="message">Message:</label>
    //<input type="text" name="message" id="field_message" placeholder="Compose Message">

    ?> -->

    <div class="small-8 text-center">
      <label for="message">Message:</label>
      <label class="required">*</label>
      <input type="text" name="message" id="field_message" placeholder="Compose Message">
    </div>

    <div class="g-recaptcha" data-sitekey="6LcJdAgTAAAAAFNtfMQDBij8f1N6k8nCPk24ENv6"></div>


    <div>
      <input class="text-center small-4 small-offset-4 submit" type="submit" name="submit" id="field_submit" value="Send Message">
    </div>

  </form>
</div>
