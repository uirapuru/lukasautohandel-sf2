set :application, "lukas"
set :domain,      "uirapuru.vot.pl"
set :deploy_to,   "/home/uirapuru/domains/lukasautohandel.de"
set :app_path,    "app"
set :php_bin, '/usr/local/php5.4/bin/php'

set :user, "uirapuru"

set :ssh_options, {
    :forward_agent => true,
    :auth_methods => ["publickey"],
    :port => 59184
}

default_run_options[:pty] = true

set :repository,  "git@github.com:uirapuru/lukasautohandel-sf2.git"
set :scm,         :git
set :branch, 	   "develop"
set :deploy_via,   :remote_cache
set :copy_via, :scp

set :interactive_mode, false

set :use_composer, true
set :composer_options,  "--no-progress --no-interaction --no-dev --no-ansi --verbose --prefer-dist --optimize-autoloader"
set :update_vendors, false
set :vendors_mode, "install"
set :cache_warmup, true

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", app_path + "/spool"]

set :writable_dirs,       ["app/cache", "app/logs"]
set :webserver_user,      "www-data"
set :permission_method,   :acl
set :use_set_permissions, true

set :model_manager, "doctrine"

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :keep_releases,  3

set :use_sudo,  false

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

# Run migrations before warming the cache
after "deploy:restart", "assets"
# after "deploy:restart", "deploy:cleanup"

# Custom(ised) tasks

set :opt, {
	:via => :scp,
	:recursive => true,
	:forward_agent => true,
	:auth_methods => ["publickey"],
	:verbose => true
}

task :assets, :except => { :no_release => true }, :roles => :app do
    capifony_pretty_print "--> Copying web/js"
	upload("web/js",		current_path + "/web/js", opt)
    capifony_puts_ok

    capifony_pretty_print "--> Copying web/css"
	upload("web/css",		current_path + "/web/css", opt)
    capifony_puts_ok

    capifony_pretty_print "--> Copying web/images"
	upload("web/images",	current_path + "/web/images", opt)
    capifony_puts_ok

    capifony_pretty_print "--> Copying web/fonts"
	upload("web/fonts",		current_path + "/web/fonts", opt)
    capifony_puts_ok

    capifony_pretty_print "--> Copying web/flags"
	upload("web/flags",		current_path + "/web/flags", opt)
    capifony_puts_ok

    capifony_pretty_print "--> Copying web/bundles"
	upload("web/bundles",	current_path + "/web/bundles", opt)
    capifony_puts_ok
end
