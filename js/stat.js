'use strict';

var fs = require('fs');

// fs.stat('useStrict.html', function (err, stat) {
//     if (err) {
//         console.log(err);
//     } else {
//         // 是否是文件:
//         console.log('isFile: ' + stat.isFile());
//         // 是否是目录:
//         console.log('isDirectory: ' + stat.isDirectory());
//         if (stat.isFile()) {
//             // 文件大小:
//             console.log('size: ' + stat.size);
//             // 创建时间, Date对象:
//             console.log('birth time: ' + stat.birthtime);
//             // 修改时间, Date对象:
//             console.log('modified time: ' + stat.mtime);
//         }
//     }
// });


try {
    var statSync = fs.statSync('useStrict.html');
} catch (error) {
    console.log('error: ' + error);
}

// 是否是文件
console.log('isFile: ' + statSync.isFile());

// 是否是目录
console.log('isDirectory: ' + statSync.isDirectory());

if (statSync.isFile()) {
    // 文件大小:
    console.log('size:' + statSync.size);

    // 创建时间:
    console.log('birth time: ' + statSync.birthtime);

    // 修改时间:
    console.log('modified time: ' + statSync.mtime);
}