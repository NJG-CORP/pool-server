**Список приглашений**

**GET /invitations/list**
```
    None
```
```
    {
        invitations: [
            {
                id: number,
                inviter_id: number,
                invited_id: number,
                club_id: number,
                accepted: boolean,
                meeting_at: date,
                created_at: date,
                updated_at: date
            }
        ]
    }
```

**Посмотреть приглашение**

**GET /invitations/{INVITATION_ID}**
```
    None
```
```
    {
        invitation: {
            id: number,
            inviter_id: number,
            invited_id: number,
            club_id: number,
            accepted: boolean,
            meeting_at: date,
            created_at: date,
            updated_at: date
        }
    }
```

**Отправить приглашение на игру**

**POST /invitations/send**
```
    >invited_id!
    >club_id!
    >meeting_at! //datetime
```
```
    {
        invitation: {
            id: number,
            inviter_id: number,
            invited_id: number,
            club_id: number,
            accepted: boolean,
            meeting_at: date,
            created_at: date,
            updated_at: date
        }
    }
```

**Принять приглашение на игру**

**POST /invitations/accept/{INVITATION_ID}**
```
    None
```
```
    {
        invitation: {
            id: number,
            inviter_id: number,
            invited_id: number,
            club_id: number,
            accepted: boolean,
            meeting_at: date,
            created_at: date,
            updated_at: date
        }
    }
```

**Отклонить приглашение на игру**

**POST /invitations/reject/{INVITATION_ID}**
```
    None
```
```
    {
        invitation: {
            id: number,
            inviter_id: number,
            invited_id: number,
            club_id: number,
            accepted: boolean,
            meeting_at: date,
            created_at: date,
            updated_at: date
        }
    }
```


