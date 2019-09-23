
require('dotenv').config()

var mysql = require('mysql')
const Telegraf = require('telegraf')
const session = require('telegraf/session')
const Stage = require('telegraf/stage')
const Markup = require('telegraf/markup')
const WizardScene = require('telegraf/scenes/wizard')
const bot = new Telegraf(process.env.BOT_TOKEN)

const mysqlConfig = {
    host: process.env.DB_HOST,
    port: process.env.DB_PORT,
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME
};


bot.start((ctx) => ctx.reply('Welcome'))

const orders = new WizardScene(
    'orders',

    (ctx) => {
        ctx.editMessageText('Выберите период, за который вы хотите увидеть заказы:', Markup.inlineKeyboard([
            Markup.callbackButton('День', 'orders_day'),
            Markup.callbackButton('Неделя', 'orders_week'),
            Markup.callbackButton('Месяц', 'orders_month'),
        ]).extra())

        return ctx.scene.leave()
    },
)

const stage = new Stage()

stage.register(orders)

bot.use(session())
bot.use(stage.middleware())

bot.action('orders', ctx => ctx.scene.enter('orders'))
bot.action('orders_day', ctx => {

    var period = Math.round(Date.now() / 1000) - 3600 * 24
    dbQuery({
        sql: 'SELECT * from `order` where `created_at` > ? AND `status` != 5 ORDER BY `created_at` DESC',
        timeout: 20000,
    }, function (result, fields) {
        ctx.editMessageText(getOrderText(result, 'К сожалению за последний день заказов не было', 'Заказы, которые удалось найти за последний день:\n'))
    }, [period])
})

bot.action('orders_week', ctx => {
    var period = Math.round(Date.now() / 1000) - 3600 * 24 * 7
    dbQuery({
        sql: 'SELECT * from `order` where `created_at` > ? AND `status` != 5 ORDER BY `created_at` DESC',
        timeout: 20000,
    }, function (result, fields) {
        ctx.editMessageText(getOrderText(result, 'К сожалению за последнюю неделю заказов не было', 'Заказы, которые' +
            ' удалось найти за последнюю неделю:\n'))
    }, [period])
})

bot.action('orders_month', ctx => {
    var period = Math.round(Date.now() / 1000) - 3600 * 24 * 7 * 31
    dbQuery({
        sql: 'SELECT * from `order` where `created_at` > ? AND `status` != 5 ORDER BY `created_at` DESC',
        timeout: 20000,
    }, function (result, fields) {
        ctx.editMessageText(getOrderText(result, 'К сожалению за последний месяц заказов не было', 'Заказы, которые' +
            ' удалось найти за последний месяц:\n'))
    }, [period])
})

bot.help((ctx) => {
    canReply({
        id: ctx.from.id,
        success: function () {
            ctx.reply('Выберите интересующую вас команду', Markup.inlineKeyboard([
                Markup.callbackButton('Заказы', 'orders'),
            ]).extra())
        },
        error: function () {
            ctx.reply('К сожалению, вам не доступна ни одна команда.')
        }
    })
})

bot.launch()

function dbQuery(query, func, params = []) {
    const db = mysql.createConnection(mysqlConfig)

    db.connect(function (err) {
        if (err) throw err
    })
    db.query(query, params, function (err, result, fields) {
        if (err) {
            throw err
        }
        func(result, fields);
    })

    db.end()
}

function getOrderText(result, def, suc) {
    var text = def

    if (result) {
        text = suc
        result.forEach(function (elem) {
            text += '\nЗаказ: №' + elem.id + '\nЗаказчик: ' + elem.name + '\nТелефон: ' + elem.phone + '\nСумма: ₴ ' +
                elem.sum + '\nДата: ' + new Date(elem.created_at * 1000).toLocaleString('en', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: 'numeric',
                    hour12: false,
                    minute: 'numeric',
                    timeZone: 'Europe/Kiev'
                })
                + '\n'
        })
    }

    return text
}

function canReply(obj) {
    if (obj.id == process.env.ADMIN_ID) {
        obj.success();
    } else {
        obj.error();
    }
}