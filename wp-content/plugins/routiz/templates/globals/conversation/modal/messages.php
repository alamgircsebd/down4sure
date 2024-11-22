<?php
defined('ABSPATH') || exit;

global $rz_conversation;

$messages = $rz_conversation->get_messages();
$receiver_userdata = get_userdata($rz_conversation->receiver_id);
?>

<?php if (isset($receiver_userdata->display_name)): ?>
    <div class="rz-conversation-tab">
        <?php echo esc_html($receiver_userdata->display_name); ?>
    </div>
<?php endif; ?>

<div class="rz-modal-container rz-scrollbar"  >
    <div class="rz-messages" id="message_load">
        <?php if (!empty($messages) && is_array($messages)): ?>
            <?php $last_date = null; ?>
            <?php foreach ($messages as $message): ?>
                <?php $message_date = date_i18n('d-m-Y', strtotime($message->created_at)); ?>
                <?php $is_me = get_current_user_id() == $message->sender_id; ?>
                <?php if ($message_date !== $last_date): ?>
                    <div class="rz-message-date">
                        <div class="rz--date">
                            <?php echo date_i18n(get_option('date_format'), strtotime($message->created_at)); ?>
                        </div>
                    </div>
                    <?php $last_date = $message_date; ?>
                <?php endif; ?>
                <div class="rz-message rz-message-<?php echo $is_me ? 'me' : 'not-me'; ?>">
                    <div class="rz--inner">
                        <div class="rz--image">
                            <?php $user = new \Routiz\Inc\Src\User($message->sender_id); ?>
                            <?php $user_avatar = $user->get_avatar(); ?>
                            <?php if ($user_avatar): ?>
                                <img src="<?php echo esc_url($user_avatar); ?>" alt="">
                            <?php else: ?>
                                <?php echo Rz()->dummy('fas fa-user', 100, '#f1f1f1'); ?>
                            <?php endif; ?>
                        </div>
                        <div class="rz--content">
                            <div class="rz--time">
                                <?php echo date_i18n(get_option('time_format'), strtotime($message->created_at)); ?>
                            </div>
                            <div class="rz--text">
                                <?php
                                echo wp_kses(wpautop(stripslashes($message->text)), [
                                    'br' => [],
                                    'p' => [],
                                    'u' => [],
                                    'strong' => []
                                ]);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="rz-text-center rz-mb-0 rz-mt-2"><?php esc_html_e('No messages', 'routiz'); ?></p>
        <?php endif; ?>
    </div>
</div>
<div class="rz-modal-footer rz--top-border rz-text-center">
                <div class="rz-conversation-input">
                    <?php wp_nonce_field( 'routiz_message_nonce', 'routiz_message' ); ?>
                    <div class="rz-message-footer">
                        <input type="hidden" id="rz_message_listing_id" value="<?php echo $rz_conversation->listing->id; ?>">
                        <input type="hidden" id="rz_message_conversation_id" value="<?php echo $rz_conversation->id; ?>">
                        <textarea id="rz_message" name="rz_message" value="" placeholder="<?php esc_html_e( 'Enter your message here ...', 'routiz' ); ?>"></textarea>
                        <a href="#" class="rz--button"  data-action="send-message">
                            <i class="fas fa-paper-plane"></i>
                        </a>
                    </div>
                </div>
        </div>

<!-- <script type="text/javascript">
    jQuery(document).ready(function($) {
        function fetchMessages() {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'fetch_latest_messages',
                    conversation_id: <?php echo $rz_conversation->id; ?>  // Ensure this is set correctly
                },
                success: function(response) {
                    if (response.success) {
                        var $messageLoad = $('#message_load');
                        // Update the HTML with new messages

                         $messageLoad.html(response.data);
                        //  Scroll to the bottom of the message container after content update
                         $messageLoad.animate({ scrollTop: $messageLoad[0].scrollHeight }, 'slow');
                         
                         let isScrolledToBottom = true; 
                    // Tracks if user is scrolled to bottom
                    } else {
                        console.error('AJAX Error:', response.data);
                    }
                },
                error: function(xhr, status, error) {   
                    console.error('Request Error:', error);
                }
            });
        }
 
        // Fetch messages every 2 seconds
        setInterval(fetchMessages, 2000);
    });
</script> -->





<script>
	jQuery(document).ready(function($) {
    function fetchMessages() {
        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'fetch_latest_messages',
                conversation_id: <?php echo $rz_conversation->id; ?>
            },
            success: function(response) {
                if (response.success) {
                    var $messageLoad = $('#message_load');
                    $messageLoad.html(response.data);
                    scrollToBottom($messageLoad); // Call scroll function here
                } else {
                    console.error('AJAX Error:', response.data);
                }
            },
            error: function(xhr, status, error) {   
                console.error('Request Error:', error);
            }
        });
    }

    function scrollToBottom($element) {
        $element.animate({ scrollTop: $element[0].scrollHeight }, 'slow');
    }

    // Fetch messages every 2 seconds
    setInterval(fetchMessages, 2000);

    // Scroll to bottom when the modal is opened
    $('.rz-modal-container').on('show.bs.modal', function() {
        scrollToBottom($('#message_load'));
    });

    // Detect keypress on the textarea
    $('#rz_message').on('keypress', function(e) {
        if (e.which == 13 && !e.shiftKey) {
            e.preventDefault();
            $('.rz--button[data-action="send-message"]').click();
        }
    });
});
	
	

	
// jQuery(document).ready(function($) {
//     // Detect keypress on the textarea
//     $('#rz_message').on('keypress', function(e) {
//         // Check if the pressed key is "Enter"
//         if (e.which == 13 && !e.shiftKey) {
//             e.preventDefault(); // Prevent new line on "Enter"
//             // Trigger the send message action
//             $('.rz--button[data-action="send-message"]').click();
//         }
//     });
// });
	
// function scrollToBottom() {
//     var chatBox = $(".rz-scrollbar"); // Chat box ki ID ya class
//     chatBox.scrollTop(chatBox.prop("scrollHeight"));
// }
 </script>