
# Gilded Rose

## ¿Qué pasa en cada turno?
Cada día, el sistema va a revisar todos los artículos en el inventario y actualizar dos cosas:
1. **`sellIn`**: Los días que quedan para vender el artículo. Este número disminuye cada día.
2. **`quality`**: La calidad del artículo. Este valor también cambia todos los días de acuerdo con ciertas reglas que se aplican a los diferentes tipos de artículos.

### **Reglas de negocio**

#### **Reglas Generales**

1. **La calidad nunca puede ser negativa**. Siempre será como mínimo 0.

2. **La calidad nunca puede ser mayor a 50**. Sin embargo, **"Sulfuras"** es una excepción a esta regla y siempre tendrá una calidad de 80.

3. **Cuando un artículo expira** (es decir, cuando el `sellIn` es menor a 0), su calidad se degrada **el doble de rápido**.

#### **Tipos de Artículos y sus Reglas**

1. **Items Normales** (cualquier artículo que no sea especial):
   - **Antes de expirar (sellIn > 0)**: La calidad se reduce **1 por día**.
   - **Después de expirar (sellIn < 0)**: La calidad se reduce **2 por día**.

2. **Aged Brie**:
   - **Antes de expirar (sellIn > 0)**: La calidad **aumenta 1 por día**.
   - **Después de expirar (sellIn < 0)**: La calidad **aumenta 2 por día**.
   - La calidad **nunca puede ser mayor a 50**. Si la calidad llega a 50, no aumenta más.

3. **Backstage passes to a TAFKAL80ETC concert**:
   - **Antes de expirar (sellIn > 0)**:
     - Si `sellIn` es mayor a 10 días: La calidad **aumenta 1 por día**.
     - Si `sellIn` es 10 días o menos: La calidad **aumenta 2 por día**.
     - Si `sellIn` es 5 días o menos: La calidad **aumenta 3 por día**.
   - **Después de expirar (sellIn < 0)**: La calidad se **establece en 0**.
   - La calidad **nunca puede superar 50** antes de expirar. Si la calidad llega a 50, no sube más.

4. **Sulfuras, Hand of Ragnaros** (Artículo legendario):
   - Este artículo **no cambia nunca**:
     - `sellIn` **no cambia**.
     - `quality` **siempre es 80**.
   - **No pierde calidad**, sin importar cuánto pase el tiempo.

5. **Conjured Items** (ej. Conjured Mana Cake):
   - Los artículos **"Conjured"** se degradan **el doble de rápido** que los artículos normales:
     - **Antes de expirar (sellIn > 0)**: La calidad disminuye **2 por día**.
     - **Después de expirar (sellIn < 0)**: La calidad disminuye **4 por día**.
   - **La calidad nunca puede ser negativa**. Si la calidad es 0, no puede seguir disminuyendo.

---

## **Cómo funciona el sistema de actualizaciones**

El sistema va a recorrer todos los artículos del inventario y actualizar su calidad y `sellIn` en función de las reglas anteriores.

### 1. **Clasificación de los artículos**

El sistema primero clasifica cada artículo en uno de estos tipos:

- **Normal** (todos los artículos que no sean especiales)
- **Aged Brie**
- **Backstage Passes**
- **Sulfuras**
- **Conjured**

Esto lo hace revisando el **nombre del artículo**. Por ejemplo, si el artículo tiene "Conjured" en su nombre, se clasifica como **Conjured**.

### 2. **Actualizar la calidad (Antes de expirar)**

Dependiendo del tipo de artículo, el sistema ajusta la calidad. Los artículos **normales** pierden 1 de calidad cada día, mientras que los **Conjured** pierden 2.

Los artículos **Aged Brie** aumentan su calidad, y los **Backstage passes** aumentan de acuerdo con la proximidad del concierto (según el valor de `sellIn`).

### 3. **Decrementar el `sellIn`**

Todos los artículos, excepto **Sulfuras**, ven su valor de `sellIn` disminuido en 1. Esto simula el paso de un día.

### 4. **Aplicar reglas para artículos expirados**

Si el `sellIn` de un artículo se vuelve negativo (es decir, el artículo ha expirado), el sistema aplica reglas especiales:

- Los artículos **normales** y **Conjured** pierden calidad más rápidamente después de expirar.
- Los **Backstage passes** se vuelven **0** después de expirar.
- **Aged Brie** sigue aumentando su calidad, pero ahora lo hace el doble.

### 5. **Asegurar los límites de calidad**

La calidad de cualquier artículo **no puede ser menor que 0** ni mayor que 50. Si un artículo intenta pasar estos límites, el sistema lo ajusta automáticamente.

---

## **Ejemplo de funcionamiento**

Imagina que tienes estos artículos en tu inventario:

```php
$items = [
    new Item('Aged Brie', 2, 0),
    new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
    new Item('Sulfuras, Hand of Ragnaros', 0, 80),
    new Item('Conjured Mana Cake', 3, 6),
];
```


### Día 1:

- **Aged Brie**: Su calidad aumenta en 1.
- **Backstage passes**: Su calidad aumenta en 1 (tiene más de 10 días).
- **Sulfuras**: No cambia nada, siempre tiene calidad 80.
- **Conjured Mana Cake**: Su calidad disminuye en 2.

### Día 2:

- **Aged Brie**: Su calidad aumenta en 1.
- **Backstage passes**: Su calidad aumenta en 1 (aún tiene más de 10 días).
- **Sulfuras**: No cambia nada.
- **Conjured Mana Cake**: Su calidad disminuye en 2.

---

## **¿Cómo ejecutar el sistema?**

Ejecuta el código para simular el paso de los días:

```bash
php ./fixtures/texttest_fixture.php 10
```

Esto simulará el proceso de actualización durante 10 días. Puedes cambiar el número de días modificando el valor al final del comando.
---

## **Pruebas**

El proyecto utiliza PHPUnit para las pruebas unitarias. Para ejecutar las pruebas, puedes usar el siguiente comando:

```bash
composer tests
```
