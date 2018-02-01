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
        user: User | null
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
