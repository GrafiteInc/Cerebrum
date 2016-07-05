# Remember

**Remember** - The easiest way to add caching to any part of your Laravel/Lumen app.

Remember is a trait which can be added to any class in your app to enable caching.

**Author(s):**
* [Matt Lantz](https://github.com/mlantz) ([@mattylantz](http://twitter.com/mattylantz), matt at yabhq dot com)

## Requirements

1. PHP 5.6+
3. Lumen 5.2+
3. Laravel 5.2+

### Composer
Start a new Laravel project:
```php
composer create-project laravel/lumen your-project-name
```

Then run the following to add Remember
```php
composer require yab/remember
```

### Providers

#### For Lumen:
Add this to the `bootsrap/app.php` in the service providers array:

#### For Laravel:
Add this to the `config/app.php` in the service providers array:

```php
Yab\Remember\RememberProvider::class
```

## How To

A general way would be to implement this on a class that may require some caching, this could be a repository or service class. You add the trait to the class

```php
use Yab\Remember\Remember;

class SomethingRepository
{
    public function __construct()
    {
        $this->memory(15);
        $this->forgetful([]); // list classes that may need to clear on an update
    }

    public function findById($id)
    {
        return $this->remember($this->model->findById($id));
    }

    public function update($id, $data)
    {
        $this->forget($id);
        return $this->model->update($id, $data);
    }
}
```

Regarding `$this->forgetful` if you do not set it, then Remember will parse your class for all methods and clear any related caches it can find.
You will also see that we can use the forget to discard object. The remember on the other hand, will collect a value and store it on its own key base.

## License
Remember is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Bug Reporting and Feature Requests
Please add as many details as possible regarding submission of issues and feature requests

### Disclaimer
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
