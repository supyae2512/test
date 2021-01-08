<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/slack.php';

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

// Include Hosts inventory file
inventory('./deploy/hosts.yml')
    ->set('writable_mode', 'chmod')
    ->set('branch', 'master')
    ->set('http_user', 'www-data')
    ->set('http_group', 'www-data')
    ->become('root');

// Project name
set('application', 'pithos');

// Project repository
set('repository', 'git@bitbucket.org:5degrees/pithos.git');

// Allocate tty for git clone. Default value is false.
set('git_tty', false);

// keep the last 5 releases
set('keep_releases', 3);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', [
    'app/storage/cache',
    'app/storage/logs',
    'app/storage/meta',
    'app/storage/sessions',
    'app/storage/views',
    'app/public/posts'
]);

// Writable dirs by web server
add('writable_dirs', [
    'storage',
    'vendor',
    'bootstrap/cache',
    'app/public/posts'
]);

// Add Slack Webhook to trigger Notifications
set('slack_webhook', 'https://hooks.slack.com/services/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');

// Slack Notifications
before('deploy', 'slack:notify');
after('success', 'slack:notify:success');

// Tasks
task('build', function () {
    run('cd {{release_path}} && build');
});

// Set directory owner
task('change:owner', function () {
    run('chown -R {{http_user}}:{{http_group}} {{deploy_path}}');
});

// Copy .env file according to environment
task('copy:env', function () {

    $stage = null;
    if (input()->hasArgument('stage')) {
        $stage = input()->getArgument('stage');
    }

    if ($stage === 'staging') {
        run('cp {{release_path}}/.env.staging {{release_path}}/.env');
        run('cd {{deploy_path}}/release && mv .env.staging {{deploy_path}}/shared/.env');
    } elseif ($stage === 'production') {
        run('cp {{release_path}}/.env.production {{release_path}}/.env');
        run('cp {{deploy_path}}/.env.production {{deploy_path}}/shared/.env');
    } else {
        return 'Environment file could not be created because no environment argument was given!';
    }
});

// generate application key
task('artisan:generate_key', function () {
    run('cd {{deploy_path}}/current && php artisan key:generate');
});

task('deploy:symlink', function () {
    run('cd {{deploy_path}} && {{bin/symlink}} {{release_path}} current');
});

task('composer:install', function () {
    run('cd {{deploy_path}}/current && composer install');
});

task('config:clear', function () {
    run('cd {{deploy_path}}/current && php artisan config:cache');
});

// Restart PHP-FPM after deploying code
task('php-fpm:restart', function () {
    run('service php7.2-fpm restart');
});

// Skip databse migration
task('artisan:migrate', function () {
    return 'Skipping migration for {{stage}}...';
});

// Skip database seeding
task('artisan:db:seed', function () {
    return 'Skipping database seeding for {{stage}}...';
});

after('deploy:update_code', 'change:owner');
after('change:owner', 'copy:env');
after('copy:env', 'deploy:symlink');
after('deploy:symlink', 'composer:install');
after('composer:install', 'config:clear');
after('config:clear', 'artisan:generate_key');
after('artisan:generate_key', 'php-fpm:restart');

// If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
//before('deploy:symlink', 'artisan:migrate');
