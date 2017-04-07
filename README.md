WarehouseApp
============

Entities
------

### Customer
name: string
email: string
address: text

### Product
name: string
type: integer (0 = standard, 1 = toxic, 2 = perishable, 3 = luxury )
quantity: integer
expire_at: date
added_at: date
location: Storage

### Storage
name: string
is_toxic: boolean

### Inventory
product: Product
storage: Storage
quantity: integer

### DeliveryOrder
order_date: date
status: string
customer: Customer

### DeliveryOrderItems
delivery_order: DeliveryOrder
product: Product
quantity: integer








