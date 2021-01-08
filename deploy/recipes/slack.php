<?php
/* (c) David Ciulla <dave@5degrees.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Deployer;

use Deployer\Utility\Httpie;

// Title of project
set('slack_title', function () {
    return get('application', 'Project');
});

// Deploy message
set('slack_text', '_{{user}}_ deploying `{{branch}}` to *{{target}}*');
set('slack_success_text', 'Deploying to *{{target}}* has been successful.');
set('slack_failure_text', 'Deploying to *{{target}} failed!*');

// Color of attachment
set('slack_color', '#4d91f7');
set('slack_success_color', '#008141');
set('slack_failure_color', '#ff0000');

// Tasks
desc('Notifying Slack');
task('slack:notify', function () {
    if (!get('slack_webhook', false)) {
        return;
    }

    $attachment = [
        'title' => get('slack_title'),
        'text' => get('slack_text'),
        'color' => get('slack_color'),
        'mrkdwn_in' => ['text'],
    ];

    Httpie::post(get('slack_webhook'))->body(['attachments' => [$attachment]])->send();
})
    ->once()
    ->shallow()
    ->setPrivate();

desc('Notifying Slack about successful deployment');
task('slack:notify:success', function () {
    if (!get('slack_webhook', false)) {
        return;
    }

    $attachment = [
        'title' => get('slack_title'),
        'text' => get('slack_success_text'),
        'color' => get('slack_success_color'),
        'mrkdwn_in' => ['text'],
    ];

    Httpie::post(get('slack_webhook'))->body(['attachments' => [$attachment]])->send();
})
    ->once()
    ->shallow()
    ->setPrivate();

desc('Notifying Slack about failed deployment');
task('slack:notify:failure', function () {
    if (!get('slack_webhook', false)) {
        return;
    }

    $attachment = [
        'title' => get('slack_title'),
        'text' => get('slack_failure_text'),
        'color' => get('slack_success_color'),
        'mrkdwn_in' => ['text'],
    ];

    Httpie::post(get('slack_webhook'))->body(['attachments' => [$attachment]])->send();
})
    ->once()
    ->shallow()
    ->setPrivate();
