---
layout: default
title: Upgrading from 0.1 to 0.2 versions
permalink: /docs/upgrading/0.1-0.2/
---

Due to the heavy work and changes in the way data is treated it was nearly impossible for us to keep the data compatible. Therefor we decided to create a complete new fresh set of migration scripts for people only just starting to use the module.

We have made sure that the old tables can be migrated up to the point of the new 0.2 versions by using the scripts in the `/src/migrations/0.1-0.2` folder. You can run them with the following command:

```
php yii migrate --migrationPath=@bedezign/yii2/audit/migrations/0.1-0.2
```

Please note that this is in structure only. The changes were too big to keep your data in working order. It might be best to move the old tables out of the way, if you choose to keep the data (or remove them completely) and use the new migrations to start anew. This will potentially save you a lot of time. 

We are very sorry for the inconvenience but promise you that taking the plunge will be worth it, we have all sorts of new goodies!

