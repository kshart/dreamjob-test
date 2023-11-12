export interface Me {
  id: number
  name: string
  phone: string
  email: string
  created_at: string
  updated_at: string
}

interface CreateUser {
  name: string
  phone: string
  password: string
  email?: string
}

const meInternal = (): Promise<Me> => {
  return fetch('/api/site/me')
    .then(response => {
      if (!response.ok) {
        return null
      }
      return response.json()
    })
    .catch(error => {
      console.error(error)
      return null
    })
}

export default {
  /**
   * Текущий залогиненый пользователь
   */
  me (): Promise<Me|null> {
    return (window as any).meResolve as Promise<Me>
  },

  /**
   * Залогиниться
   * Авторизация через токен в куках
   */
  login (phone: string, password: string) {
    return fetch('/api/site/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        phone,
        password,
      })
    })
      .then(({ ok, body }) => {
        if (!ok) {
          return null
        }
        (window as any).meResolve = meInternal()
        return body
      })
      .catch(error => {
        console.error(error)
        return null
      })
  },

  /**
   * Выйти
   */
  logout (): Promise<boolean> {
    return fetch('/api/site/logout', {
      method: 'POST',
    })
      .then(({ ok }) => {
        if (!ok) {
          return false
        }
        (window as any).meResolve = new Promise((resolve) => resolve(false))
        return true
      })
      .catch(error => {
        console.error(error)
        return false
      })
  },

  /**
   * Создать пользователя
   */
  createUser (fields: CreateUser) {
    return fetch('/api/site/create-user', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(fields)
    })
      .then(response => {
        if (!response.ok) {
          response.json()
          return null
        }
        return response.json()
      })
      .catch(error => {
        console.error(error)
        return null
      })
  },
}
