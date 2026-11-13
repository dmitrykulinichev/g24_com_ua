---
title: How to write in the Telegram group
description: A cheat sheet for drivers without the app: how to take and return a vehicle, send mileage, cash, refuelling or an issue with a short message in the fleet's group.
---

# How to write in the Telegram group

If your fleet has a Telegram group, you can run your shift without the app: you send a short message and the
fleet's bot records it in the system.

## Before you start

The bot needs to know who you are. Ask the fleet administrator for the Telegram connection link, open it and tap
"Start". Without it, the bot will reply in the group that it cannot recognise you.

## How to write

A message starts with **the four digits of the plate number**: for KA 5683 AA that is `5683`. Then a word and
numbers, separated by spaces. The bot understands Ukrainian and Russian words, so the examples use them.

| What to do | Example |
|---|---|
| Take a vehicle | `5683 прийняв 76081` |
| Return a vehicle | `5683 здав 76150` |
| Send mileage | `5683 76120` |
| Cash trip | `5683 cash 500` |
| Refuelling: fuel, litres, amount | `5683 бензин 40 2200` |
| Report a fault | `5683` and a description of the problem |
| Your schedule | `schedule` |
| Your vehicle's insurance | `insurance` |

Write numbers without spaces or dots: mileage `76081`, not `76 081`. Mileage on taking and returning the vehicle
is optional, but better to include it.

Instead of "cash" you can write «рука» or «замовлення». After the cash amount you can add kilometres and a
comment: `5683 cash 500 12 airport`. Fuel: «бензин» (petrol), «дизель» or «ДТ» (diesel), «газ» or «ГБО» (LPG).

## How to tell the bot recorded it

![](screen:tg-group,tg-hints)

- 👌 under your message: recorded.
- ❌: the message was understood, but could not be recorded, for example the vehicle is taken by another driver.
  Contact the administrator.
- The bot replied in the group «Я зрозумів так…» ("I understood it as…"): it recorded the message, but you did
  not follow the format. Next time write it the way the bot suggests.
- No 👌 and no reply: the bot did not understand the message. It will send you a hint with the formats in a
  private chat.

The bot writes in Ukrainian.

## Something's not working

1. The bot says it cannot recognise you: connect Telegram with the link from the administrator (see above).
2. You don't get hints in private messages: open the fleet's bot in Telegram and tap "Start", otherwise the bot
   cannot write to you.
3. You made a mistake in a message: edit it in Telegram and the bot will re-read it. If a wrong record has
   already gone through, tell the administrator.
4. Have the Garage24 app? Recording is easier there: [Shift records](/driver/records).
