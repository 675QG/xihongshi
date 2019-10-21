function a(){
	window.onload = function() {     //资源加载结束后触发
        var canvas =document.querySelector("canvas");  //获取canvas元素
        if(canvas.getContext){   //判断上下文对象是否有效
          var ctx = canvas.getContext("2d");   //获取渲染上下文
          ctx.fillStyle = "#6A6AA7";  //填充颜色
          ctx.fillRect(50,50, 200,200);  //绘制图形
        }
    }
}


 // //使用立即执行函数封闭作用域，使外界不能访问里面的参数。
 //      (function(){
 //        var myQuery
 //        // 返回的是一个对象实例
 //        myQuery =function(){
 //            return new myQuery.prototype.xxx()
 //        }    
 //        //把方法写在myQuery 的原型上
 //        myQuery.prototype ={
 //            xxx : function (){
 //                return this  ;    //  return this 后，可以使用原型链调用
 //             }
 //        }
 //        window.$=window.myQuery=myQuery   //把myQuery暴露给外界的window上
 //        var xxx =myQuery.prototype.xxx();   //让myQuery原型上的xxx方法的原型默认指向的是object， 
 //        xxx.prototype = myQuery.prototype  //让xxx的原型指向变成myQuery ，这样方便后面的链式调用。
 //      }())
 //      