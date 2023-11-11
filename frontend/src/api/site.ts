export interface Me {
  id: number
  name: string
  phone: string
  email: string
  created_at: string
  updated_at: string
}

const meInternal = (): Promise<Me> => {
  return fetch('/api/user/me')
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
    return fetch('/api/user/login', {
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
    return fetch('/api/user/logout', {
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
}
