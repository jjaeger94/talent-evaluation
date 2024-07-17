<div class="container mt-5">
    <br>
    <p>Gerne kannst du deine Präferenzen hier noch einmal bearbeiten.</p>
    <div class="row">
        <?php foreach ($preferences as $preference): ?>
            <?php if (isset($jobs_by_id[$preference->job_id])): ?>
                <div class="col-md-4 mb-4">
                    <div class="card" data-toggle="modal" data-target="#jobModal<?php echo esc_attr($preference->job_id); ?>">
                        <div class="row card-body d-flex align-items-center edit-preference" data-id="<?php echo $preference->ID; ?>" data-titel="<?php echo esc_html($jobs_by_id[$preference->job_id]->job_title); ?>" data-info="<?php echo esc_html($jobs_by_id[$preference->job_id]->job_info); ?>">
                            <h5 class="col-10 card-title">
                                <?php echo esc_html($jobs_by_id[$preference->job_id]->job_title); ?>
                            </h5>
                            <div class="col menu-button preference-icon" id="help-matching-open-<?php echo $preference->ID; ?>">
                                <?php if ($preference->value == 2): ?>
                                    <i class="fa-solid fa-check"></i>
                                <?php else: ?>
                                    <i class="fa-solid fa-x"></i>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="jobModal" tabindex="-1" role="dialog" aria-labelledby="jobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jobModalLabel"></h5>
                <button class="btn-close" id="job-btn-close" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="preference_info"></div>
                <div class="swiper--buttons">
                    <button id="nope" value="1"><i class="fa fa-xmark"></i></button>
                    <button id="love" value="2"><i class="fa fa-heart"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        let currentPreferenceId = null;

        $('#job-btn-close').click(() => {
            currentPreferenceId = null;
            $('#jobModal').modal('hide');
        });

        // Modales Fenster öffnen, um Berufserfahrung zu bearbeiten
        $('.edit-preference').click(function() {
            currentPreferenceId = $(this).data('id');
            $('#jobModalLabel').text($(this).data('titel'));
            $('#preference_info').text($(this).data('info'));
            $('#jobModal').modal('show');
        });

        function sendSwipeAction(preferenceId, state) {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'save_preference',
                    preference_id: preferenceId,
                    talent_id: <?php echo $talent->ID; ?>,
                    preference: state
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Success: ' + response.data);
                        updatePreferenceIcon(preferenceId, state);
                    } else {
                        console.log('Error: ' + response.data);
                    }
                },
                error: function() {
                    console.log('AJAX request failed.');
                }
            });
        }

        function updatePreferenceIcon(preferenceId, state) {
            console.log('updatePreferenceIcon',preferenceId, state);
            const iconElement = $('#help-matching-open-' + preferenceId).children();
            if (state == 2) {
                iconElement.removeClass('fa-x').addClass('fa-check');
            } else {
                iconElement.removeClass('fa-check').addClass('fa-x');
            }
        }

        // Event-Listener für die Buttons
        $('#nope').click(function() {
            if (currentPreferenceId) {
                sendSwipeAction(currentPreferenceId, 1); // Nope
                $('#jobModal').modal('hide');
            }
        });

        $('#love').click(function() {
            if (currentPreferenceId) {
                sendSwipeAction(currentPreferenceId, 2); // Love
                $('#jobModal').modal('hide');
            }
        });
    });
</script>