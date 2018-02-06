**Список приглашений**

**GET /invitation/list**
```
    None
```
```
    {
        invitations: Invitation[]
    }
```

**Отправить приглашение на игру**

**POST /invitation/send**
```
    >invited_id!
    >club_id!
    >meeting_at! //datetime
```
```
    {
        invitation: Invitation
    }
```

**Принять приглашение на игру**

**POST /invitation/accept/{INVITATION_ID}**
```
    None
```
```
    {
        invitation: Invitation
    }
```


