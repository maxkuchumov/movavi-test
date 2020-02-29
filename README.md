# The task.

There are two services which returns currency rates:

-	https://www.cbr.ru/development/SXML/
-	https://cash.rbc.ru/cash/json/converter_currency_rate/?currency_from=USD&currency_to=RUR&source=cbrf&sum=1&date=


It is required to develop a library that will calculate the average euro and dollar rates using these two services for the transmitted date. If one service is unavailable, an exception should be thrown. The code should be covered as much as possible with tests.
