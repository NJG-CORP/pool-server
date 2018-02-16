**Логин**

**POST /auth/login**
```
    > email!
    > password!
```
```
    {
        token: str | null
    }
```
**Регистрация**

**POST /auth/register**
```
    > email!
    > name!
    > surname!
```
```
    {
        user: {
            id: number,
            name: string,
            surname: string,
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
