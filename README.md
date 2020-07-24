- [Running with Docker](#running-with-docker)
- [Source installation](#source-installation)
- [Features](#features)
- [Todo](#todo)
- [Screenshots](#screenshots)
- [Other tools](#other-tools)
- [Unit tests](#unit-tests)

## Running with Docker

- Repository: https://hub.docker.com/r/stephanediondev/elasticsearch-admin

```
docker run -e "ELASTICSEARCH_URL=http://x.x.x.x:9200" -e "SECRET_REGISTER=xxxxx" -p 80:8080 -d --name elasticsearch-admin stephanediondev/elasticsearch-admin

# Edit ELASTICSEARCH_URL and SECRET_REGISTER (random string to secure registration)
# If Elasticsearch security features are enabled, add -e "ELASTICSEARCH_USERNAME=xxxxx" -e "ELASTICSEARCH_PASSWORD=xxxxx"
```

## Source installation

- Web server with rewrite module enabled
- PHP 7.2.5 or higher: https://symfony.com/doc/current/setup/web_server_configuration.html
- Composer: https://getcomposer.org/download/
- npm: https://docs.npmjs.com/downloading-and-installing-node-js-and-npm

Download or clone the repository

Configure a vhost with the document root set to the "public" folder (ie /var/www/elasticsearch-admin/public)

```
cd /var/www/elasticsearch-admin/

composer install

npm install
npm run build

cp .env.dist .env

# Edit ELASTICSEARCH_URL and SECRET_REGISTER (random string to secure registration)
# If Elasticsearch security features are enabled, edit ELASTICSEARCH_USERNAME and ELASTICSEARCH_PASSWORD
```

## Features

- [x] Supported Elasticsearch versions: 2.x, 5.x, 6.x, 7.x, 8.x (snapshot)
- [x] Connection to Elasticsearch: server-side (no CORS issue), private or public, local or remote, http or https, credentials or not
- [x] App users: register, login, logout, list, create, read, update, delete
- [x] App roles: list, create, read, update (permissions), delete
- [x] Cluster: basic metrics, allocation explain [5.0], settings [5.0] (list, update)
- [x] Nodes: list, read, usage [6.0], plugins, reload secure settings [6.4]
- [x] Indices: list, reindex, create, read, update (mappings), lifecycle [6.6] (explain, remove policy), delete, close / open, freeze / unfreeze [6.6], force merge [2.1], clear cache, flush, refresh, empty [5.0], search documents, import (ODS, XLSX) / export (CSV, TSV, ODS, XLSX, GEOJSON), aliases (list, create, delete)
- [x] Index templates (legacy): list, create, read, update, delete, copy
- [x] Index templates [7.8]: list, create, read, update, delete, copy
- [x] Component templates [7.8]: list, create, read, update, delete, copy
- [x] Index lifecycle management policies [6.6]: list, status, start, stop, create, read, update, delete, copy
- [x] Shards: list
- [x] Repositories: list, create (fs, s3, gcs), read, update, delete, cleanup, verify
- [x] Snapshot lifecycle management policies [7.4]: list, status, start, stop, create, read, update, delete, execute, history, stats, copy
- [x] Snapshots: list, create, read, delete, failures, restore
- [x] Elasticsearch users (native realm): list, create, read, update, delete, enable, disable
- [x] Elasticsearch roles: list, create, read, update, delete, copy
- [x] Tasks [2.3]: list
- [x] Remote clusters [5.4]: list
- [x] Enrich policies [7.5]: list, stats, create, read, delete, execute, copy
- [x] Pipelines [5.0]: list, create, read, update, delete, copy
- [x] Cat APIs: list, export (CSV, TSV, ODS, XLSX)
- [x] Console
- [x] SQL access [6.3]: query, translate to DSL
- [x] Deprecations info [5.6]
- [x] License [5.0]: read, status / start trial / revert to basic [6.6], features
- [x] Index graveyard [5.0]: list

## Todo

- [ ] SQL access: export
- [ ] Shards: read, segments, cluster reroute (move, allocate replica, cancel)
- [ ] Indices: shrink, split, clone, import from database
- [ ] Index templates (legacy): convert to new version
- [ ] Repositories: create (url, source, hdfs, azure)
- [ ] Remote clusters: create, update, delete
- [ ] License: update

## Screenshots

[![Cluster](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-cluster.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-cluster.png)

[![Cluster settings](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-cluster-settings.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-cluster-settings.png)

[![Cluster allocation explain](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-cluster-allocation-explain.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-cluster-allocation-explain.png)

[![Nodes](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-nodes.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-nodes.png)

[![Node](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-node.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-node.png)

[![Indices](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-indices.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-indices.png)

[![Indices stats](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-indices-stats.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-indices-stats.png)

[![Index](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-index.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-index.png)

[![Create index](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-index-create.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-index-create.png)

[![Index templates](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-index-templates.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-index-templates.png)

[![Create index template](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-index-template-create.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-index-template-create.png)

[![Shards](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-shards.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-shards.png)

[![Create AWS S3 repository](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-repository-create-s3.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-repository-create-s3.png)

[![Create SLM policy](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-slm-policy-create.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-slm-policy-create.png)

[![Snaphosts](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-snapshots.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-snapshots.png)

[![Create snapshot](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-snapshot-create.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-snapshot-create.png)

[![Create enrich policy](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-enrich-create.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-enrich-create.png)

[![License](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/resized-license.png)](https://raw.githubusercontent.com/stephanediondev/elasticsearch-admin/master/screenshots/7.8.0/original-license.png)


## Other tools

- http://mobz.github.io/elasticsearch-head/
- http://www.elastichq.org/
- https://elasticvue.com/
- https://github.com/lmenezes/cerebro
- https://github.com/moshe/elasticsearch-comrade
- https://opensource.appbase.io/dejavu/
- https://www.elastic-kaizen.com/

## Unit tests

```
bin/console app:phpunit && bin/phpunit
```
