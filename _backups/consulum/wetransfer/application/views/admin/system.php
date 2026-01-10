<?php
if(!isset($settings['debug_mode']) && !isset($_GET['redirect'])) {
    echo '<script>setTimeout(function() { window.location.href = window.location.href + "?update=done&redirect=done"; }, 1000);</script>';
}
?>
    <div class="container-xl">
        <div class="page-body margins">
            <?php if(!$htaccess_check_2_3_2) : ?>
                <div class="alert alert-danger" style="margin: 10px 0 20px 0;">
                    <h4>Important!</h4>
                    It seems like you're missing the <code>application/.htaccess</code> file, please copy it over from your Chappy Social ZIP or download it from <a href="https://raw.githubusercontent.com/bcit-ci/CodeIgniter/develop/application/.htaccess" target="_blank">here</a> and place it into the application/ directory.
                    <br>
                </div>
            <?php endif; ?>

            <div class="row row-cards">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                System info
                            </h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-5">Site URL</dt>
                                <dd class="col-7"><a href="<?php echo $settings['site_url'] ?>"><?php echo $settings['site_url'] ?></a></dd>
                                <dt class="col-5">Install path</dt>
                                <dd class="col-7"><?php echo FCPATH ?></dd>
                                <dt class="col-5">Chappy Social version</dt>
                                <dd class="col-7"><?php echo $settings['version'] ?></dd>
                                <dt class="col-5">Chappy Social mode</dt>
                                <dd class="col-7"><?php echo ENVIRONMENT ?></dd>
                                <dt class="col-5">Chappy Social debug mode</dt>
                                <dd class="col-7"><?php echo $settings['debug_mode'] ?> <a href="system?action=debug" onclick="return confirm('Debugging mode should only be used for testing, it can generate a large log file on your server when you leave it enabled in production. Make sure to disable it when you\'re done with testing.')"><span class="badge bg-blue"><?php echo ($settings['debug_mode'] == 'true' ? 'Disable' : 'Enable') ?></span></a></dd>
                                <dt class="col-5">Active theme</dt>
                                <dd class="col-7"><?php echo $settings['theme'] ?></dd>
                                <dt class="col-5">Active plugins</dt>
                                <dd class="col-7">
                                    <?php
                                    foreach ($plugins as $plugin) {
                                        echo $plugin['name'] . '<br>';
                                    }
                                    ?>
                                </dd>
                                <dt class="col-5">PHP version</dt>
                                <dd class="col-7"><?php echo phpversion() ?></dd>
                                <dt class="col-5">PHP SAPI</dt>
                                <dd class="col-7"><?php echo php_sapi_name() ?></dd>
                                <dt class="col-5">PHP settings</dt>
                                <dd class="col-7">
                                    <ul style="list-style: none; padding: 0; margin: 0;">
                                        <li><b>post_max_size:</b> <?php echo ini_get('post_max_size') ?></li>
                                        <li><b>upload_max_filesize:</b> <?php echo ini_get('upload_max_filesize') ?></li>
                                        <li><b>max_execution_time:</b> <?php echo ini_get('max_execution_time') ?></li>
                                        <li><b>memory_limit:</b> <?php echo ini_get('memory_limit') ?></li>
                                        <li><b>display_errors:</b> <?php echo ini_get('display_errors') ?></li>
                                        <li><b>output_buffering:</b> <?php echo ini_get('output_buffering') ?></li>
                                    </ul>
                                </dd>
                                <dt class="col-5">PHP loaded modules</dt>
                                <dd class="col-7"><?php foreach (get_loaded_extensions() as $module) { echo '<span class="badge bg-blue-lt">' . $module . '</span> '; } ?></dd>
                                <dt class="col-5">CURL version</dt>
                                <dd class="col-7"><?php echo curl_version()['version'] ?></dd>
                                <dt class="col-5">Available storage</dt>
                                <dd class="col-7"><?php echo $settings['upload_dir'] . ' <span class="badge bg-purple-lt">' . byte_format(disk_free_space($settings['upload_dir'])) . '</span>' ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php if(isset($updated) && $updated): ?>
 
    <script>$(document).ready(function() { $('#changelog-modal').modal('show'); });</script>
<?php endif; ?>