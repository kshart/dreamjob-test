export interface Paginator<Type> {
  data: Type[]
  page: number
  perPage: number
  total: number
  totalPages: number
}
