<?php


namespace Tests\Unit\Enum;


use App\Http\Middleware\TransformEnums;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;
use Tests\Traits\CreateRequest;
use Tests\Unit\Enum\Enums\UserTypeEnum;

class EnumMiddlewareTest extends TestCase
{
    use CreateRequest;

    public function testCanTransformARequestParameterToEnumByKey()
    {
        // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）

        // 根据枚举名称来转换
        $request = $this->createRequest([
            'user_type' => 'MODERATOR',
        ]);

        $middleware = new TransformEnums([
            'user_type' => UserTypeEnum::class,
        ]);

        $middleware->handle($request, function (Request $request) {
            $this->assertInstanceOf(UserTypeEnum::class, $request->input('user_type'));
            $this->assertEquals('MODERATOR', $request['user_type']->key);
        });
    }

    public function testCanTransformARequestParameterToEnumByValue()
    {
        // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）

        // 根据枚举值来转换
        $request = $this->createRequest([
            'user_type' => 1,
        ]);

        $middleware = new TransformEnums([
            'user_type' => UserTypeEnum::class,
        ]);

        $middleware->handle($request, function (Request $request) {
            $this->assertInstanceOf(UserTypeEnum::class, $request->input('user_type'));
            $this->assertEquals('MODERATOR', $request['user_type']->key);
        });
    }

    public function testCanTransformARequestParameterToEnumByKeyWithoutStrictTypeChecking()
    {
        // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）

        // 根据枚举名称来转换
        $request = $this->createRequest([
            'user_type' => 'moderator',// strict 为 true 时，这里必须和枚举名称大小写一样
        ]);

        $middleware = new TransformEnums([
            'user_type' => UserTypeEnum::class,
        ], false);

        $middleware->handle($request, function (Request $request) {
            $this->assertInstanceOf(UserTypeEnum::class, $request->input('user_type'));
            $this->assertEquals('MODERATOR', $request['user_type']->key);
        }, false);// 设置 strict 为 false
    }

    public function testCanTransformARequestParameterToEnumByValueWithoutStrictTypeChecking()
    {
        // 通过 TransformEnums 中间件，将请求参数转换成枚举实例（需要预先定义好参数和枚举对象之间的对应关系）

        // 根据枚举值来转换
        $request = $this->createRequest([
            'user_type' => '1',// strict 为 true 时，这里必须和枚举名值类型一样
        ]);

        $middleware = new TransformEnums([
            'user_type' => UserTypeEnum::class,
        ]);

        $middleware->handle($request, function (Request $request) {
            $this->assertInstanceOf(UserTypeEnum::class, $request->input('user_type'));
            $this->assertEquals('MODERATOR', $request['user_type']->key);
        }, false);// 设置 strict 为 false
    }

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('enum', [
            'localization' => ['key' => 'enums'], 'transformations' => ['user_type' => UserTypeEnum::class],
        ]);
    }
}
