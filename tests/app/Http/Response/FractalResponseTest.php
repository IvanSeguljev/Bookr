<?php

namespace tests\app\Http\Response;

use TestCase;
use Mockery as m;
use League\Fractal\Manager;
use League\Fractal\Serializer\SerializerAbstract;
use App\Http\Response\FractalResponse;
use League\Fractal\TransformerAbstract;
use League\Fractal\Scope;

class FractalResponseTest extends TestCase{
    
    /** @test **/
    public function can_be_initialized()
    {
        $manager = m::mock(Manager::class);
        $serializer = m::mock(SerializerAbstract::class);
        
        $manager->shouldReceive('setSerializer')->with($serializer)->once()->andReturn($manager);
        
        $fractal = new FractalResponse($manager,$serializer);
        
        $this->assertInstanceOf(FractalResponse::class,$fractal);
    }
    
    /** @test **/
    public function it_can_transform_an_item()
    {
        $transformer = m::mock(TransformerAbstract::class);
        
        $scope = m::mock(Scope::class);
        $scope->shouldReceive('toArray')->once()->andReturn(['foo'=>'bar']);
        
        $serializer = m::mock(SerializerAbstract::class);
        
        $manager = m::mock(Manager::class);
        $manager->shouldReceive('setSerializer')->with($serializer)->once();
        
        $manager->shouldReceive('createData')->once()->andReturn($scope);
        
        $subject = new FractalResponse($manager,$serializer);
        $this->assertInternalType('array',$subject->item(['foo'=>'bar'],$transformer));
    }
    
    /** @test **/
    public function it_can_transform_a_collection()
    {
        $transformer = m::mock(TransformerAbstract::class);
        
        $data = ['foo' =>'bar'];
        
        $scope = m::mock(Scope::class);
        $scope->shouldReceive("toArray")->once()->andReturn($data);
        
        $serializer = m::mock(SerializerAbstract::class);
        
        $manager = m::mock(Manager::class);
        $manager->shouldReceive("setSerializer")->with($serializer)->once();
        $manager->shouldReceive("createData")->once()->andReturn($scope);
        
        $subject = new FractalResponse($manager,$serializer);
        
        $this->assertInternalType('array',$subject->collection($data,$transformer));
    }
}