# 0.3.0

- Rename package (namespace is now Slm\Queue instead of SlmQueue, composer is now slm/queue instead of juriansluiman/slm-queue)
- Add queue features
- Add support for events
- Add support for Doctrine based queues with SlmQueueDoctrine

# 0.2.4

- Add support for signals to stop worker properly

# 0.2.3

- Fix compatibilities problems with PHP 5.3

# 0.2.2

- Fix compatibilities problems with PHP 5.3

# 0.2.1

- Fix the default memory limit of the worker (from 1KB, which was obviously wrong, to 100MB)

# 0.2.0

- This version is a complete rewrite of SlmQueue. It is now splitted in several modules and support both
Beanstalkd and Amazon SQS queue systems through SlmQueueBeanstalkd and SlmQueueSqs modules.
