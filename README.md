# Cerebrum

**Cerebrum** - The easiest way to add some mental magic to any part of your Laravel/Lumen app.

* Memory provides magic caching abilities.
* Linguistics provides a means of simple NLP.
* Perception helping you discover data you didn't even know you had

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

Then run the following to add Cerebrum
```php
composer require yab/cerebrum
```

### Providers

```php
Yab\Cerebrum\CerebrumProvider::class
```

#### For Lumen:
Add this to the `bootsrap/app.php` in the service providers array:

#### For Laravel:
Add this to the `config/app.php` in the service providers array:

## Milestones

### 1.0
- [x] Memory
- [x] Linguistics

### 1.1
- [x] Perception

## Memory Example
```php
use Yab\Cerebrum\Memory;

class TaskService
{
    use Memory;

    public function __construct(TaskRespository $taskRepository)
    {
        $this->repository = $taskRepository;
        // provided by Memory
        $this->memoryDuration(15);
        $this->forgetful([
            'all'
        ]);
    }

    public function all()
    {
        return $this->remember($this->repository->all());
    }

    public function findById($id)
    {
        return $this->remember($this->repository->findById($id));
    }

    public function findByIdWithLongerCache($id)
    {
        return $this->remember($this->repository->findById($id), 1440);
    }

    public function update($id, $data)
    {
        $this->forget($id);
        return $this->repository->update($id, $data);
    }
}
```

Regarding `$this->forgetful` if you do not set it, then `Memory` will parse your class for all functions and clear any related caches it can find.

The `remember` function on the other hand, will collect a value and store it in the cache,
returning the cached version. The `forget` with a parameter will find caches with similar values and clear your caches.

## Linguistics Example
```php
use Yab\Cerebrum\Linguistics;

class TaskService
{
    use Linguistics;

    public function __construct(TaskRespository $taskRepository)
    {
        $this->repository = $taskRepository;
    }

    public function getKeyWords($id)
    {
        return $this->getKeyWords($this->repository->findById($id)->text);
    }

    public function search($searchString)
    {
        if ($this->isQuestion($seachString)) {
            return $this->repository->search();
        }
    }
}
```

## Perception Examples
```php
use Yab\Cerebrum\Perception;

class TaskService
{
    use Perception;

    public function __construct(TaskRespository $taskRepository)
    {
        $this->repository = $taskRepository;
    }

    public function getNormalizedData()
    {
        $scores = $this->repository->pluck('score')->all();
        return $this->normalize($scores);
    }

    public function predictDay()
    {
        $records = $this->repository->pluck('day', 'sleep_hours', 'active_hours')->all();
        // $records = ['monday' => [6, 1], 'wednesday' => [4, 0]]
        $result = $this->supervised()->samples($records)->predict([5,2]);
        // $result = monday
    }

    public function getExpectingDay()
    {
        // This is a very simple frequency tool
        $records = $this->repository->pluck('day')->all();
        // $records = ['monday', 'tuesday', 'wednesday', 'tuesday', 'friday', 'monday', 'tuesday']
        $result = $this->ai()->samples($records)->expecting();
        // $result = tuesday
    }
}

## License
Cerebrum is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### Bug Reporting and Feature Requests
Please add as many details as possible regarding submission of issues and feature requests

### Disclaimer
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
