**Логин**

**POST /auth/login**
```
    > email!
    > password!
```
```
    {
        token: string, 
        user: {
            id: number,
            name: string,
            surname: string,
            source: string,
            external_id: number,
            api_token: string
        } 
    }
```

**Проверка регистрации через соц. сети**

**POST /auth/social**
```
    > email!
    > external_id!
    > source!
```
```
    {
        token: string, 
        user: {
            id: number,
            name: string,
            surname: string,
            source: string,
            external_id: number,
            api_token: string
        } 
    }
```

**Регистрация**

**POST /auth/register**
```
    > email!
    > name!
    > surname!
    > source?
    > external_id?
```
```
    {
        user: {
            id: number,
            name: string,
            surname: string,
            source: string,
            external_id: number,
            api_token: string
        }
    }
```

**Сброс пароля**

**POST /auth/reset**
```
    > email!
```
```
    {
        reset: bool
    }
```
