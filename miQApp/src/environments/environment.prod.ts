export const environment = {
  production: true,
//  baseUrl: 'http://dev.codingelephant.tech/miQServer/public/api/',
  baseUrl: 'http://13.229.202.163/miQServer/public/api/',
 // baseUrl: 'http://192.168.100.131/',
  urlPrefix: '/app',
  pathPrefix:'app',
  MIX_PUSHER_APP_KEY:'ABCDEFG',
  MIX_PUSHER_APP_CLUSTER:'mt1',
  socketUrl: '13.229.202.163',
  // socketUrl: '192.168.100.131',
  socketPort: 6001
};

export const prod_environment = {
  production: true,
  //baseUrl: 'http://dev.codingelephant.tech/miQServer/public/api/'
     baseUrl: 'http://13.229.202.163/miQServer/public/api/'
    // baseUrl: 'http://192.168.100.131/api/'
};


