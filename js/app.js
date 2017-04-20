'use strict';

const Koa = require('koa');
const app = new Koa();

app.use( async (ctx, next) => {
    await next();

    console.log('first async function');

    ctx.response.type = 'text/html';
    ctx.response.body = '<h1>hello koa!</h1>';

});

app.use( async (ctx, next) => {
    await next();

    console.log('second async function');
});


app.listen(3000);

console.log('listen 3000');

