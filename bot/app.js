require('dotenv').config()
const os = require('os')

const Telegraf = require('telegraf')

const bot = new Telegraf(process.env.BOT_TOKEN)

bot.start((ctx) => ctx.reply('Welcome'))
bot.help((ctx) => ctx.reply('Send me a sticker'))
bot.on('sticker', (ctx) => ctx.reply('👍'))
bot.hears('hi', (ctx) => ctx.reply(`I\'m working on ${os.type()}`))

bot.command('oldschool', (ctx) => ctx.reply('Hello'))
bot.command('modern', ({reply}) => reply('Yo'))
bot.command('hipster', Telegraf.reply('λ'))

bot.launch()