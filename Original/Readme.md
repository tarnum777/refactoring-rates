## Task

1. Refactor `code.php` file. 
2. Check the `Notes about this code` and `Requirements for your code` sections for more direction.

## Notes about this code

1. Idea is to calculate commissions for already made transactions;
2. Transactions are provided each in it's own line in the input file, in JSON;
3. BIN number represents first digits of credit card number. They can be used to resolve country where the card was issued;
4. We apply different commission rates for EU-issued and non-EU-issued cards;
5. We calculate all commissions in EUR currency.

## Requirements for your code

1. README: clearly list things included / exluded, trade offs considered. 
2. It must have unit tests. If you haven't written any previously, please take the time to learn it before making the task, you'll thank us later.
   - Unit tests must test the actual results and still pass even when the response from remote services change (this is quite normal, exchange rates change every day). This is best accomplished by using mocking.
3. As an improvement, add ceiling of commissions by cents. For example, 0.46180... should become 0.47.
4. It should give the same result as original code in case there are no failures, except for the additional ceiling.
5. Code should be extendible – we should not need to change existing, already tested functionality to accomplish the following:
   - Switch our currency rates provider (different URL, different response format and structure, possibly some authentication);
   - Switch our BIN provider (different URL, different response format and structure, possibly some authentication)
   - Just to note – no need to implement anything additional. Just structure your code so that we could implement that later on without braking our tests;
6. It should look as you'd write it yourself in production – consistent, readable, structured. Anything we'll find in the code, we'll treat as if you'd write it yourself. Basically it's better to just look at the existing code and re-write it from scratch. For example, if 'yes'/'no', ugly parsing code or die statements are left in the solution, we'd treat it as an instant red flag.
7. Use composer to install testing framework and any needed dependencies you'd like to use, also for enabling autoloading.

## What gets evaluated

- Are requirements fulfilled
- Clean code / best practices
- Tests have to be meaningful and green