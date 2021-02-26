# A newsletter subscription app

A newsletter subscription app with a file storage adapter. It writes data in JSON to the filesystem.
- \App\Storage\SurveyStorageInterface::persist and \App\Storage\SubscriberStorageInterface::persist either create
a new record or update an existing one.

## Run the server:
```
symfony server:start --port=8000
```
Go to http://localhost:8000/subscriber. After finishing the 2 forms 

## Example usage on the command line

```php
# See src/Command/TestCommand

        $subscriber = new Subscriber('peter@example.com', 'Peter');
        $this->subscriberStorage->persist($subscriber);

        $this->subscriberStorage->persist(new Subscriber('arnoldas@example.com', 'Arnoldas'));
        $this->subscriberStorage->persist(
            (new Subscriber('arnoldas@example.com', 'Peter S.'))
                ->setId($subscriber->getId())
        );

        $survey = (new Survey())
            ->setCategories(['foo', 'bar'])
            ->setSubscriberId($subscriber->getId())
        ;
        $this->surveyStorage->persist($survey);

        $symfonyStyle = new SymfonyStyle($input, $output);

        $symfonyStyle->section('Subscribers');
        VarDumper::dump($this->subscriberStorage->getSubscriberCollection());

        $symfonyStyle->section('Surveys');
        foreach ($this->surveyStorage->getSurveyCollection()->getSurveys() as $survey) {
            VarDumper::dump($survey);
        }
```

Output:
```
Subscribers
-----------

App\Dto\SubscriberCollection {#251
  -subscribers: array:2 [
    "90722085#1614317094.2398" => App\Entity\Subscriber {#252
      -id: "90722085#1614317094.2398"
      -email: "arnoldas@example.com"
      -name: "Peter S."
    }
    "64917208#1614317094.2399" => App\Entity\Subscriber {#253
      -id: "64917208#1614317094.2399"
      -email: "arnoldas@example.com"
      -name: "Arnoldas"
    }
  ]
}
Surveys
-------

App\Entity\Survey {#253
  -id: "18318401#1614317094.2404"
  -subscriberId: "90722085#1614317094.2398"
  -categories: array:2 [
    0 => "foo"
    1 => "bar"
  ]
}

```

