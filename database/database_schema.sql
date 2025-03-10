USE [master]
GO
/****** Object:  Database [ecommerceDB]    Script Date: 05/03/2025 3:19:58 pm ******/
CREATE DATABASE [ecommerceDB]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'ecommerceDB_Data', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL16.SQLEXPRESS\MSSQL\DATA\ecommerceDB.mdf' , SIZE = 8192KB , MAXSIZE = UNLIMITED, FILEGROWTH = 1024KB )
 LOG ON 
( NAME = N'ecommerceDB_Log', FILENAME = N'C:\Program Files\Microsoft SQL Server\MSSQL16.SQLEXPRESS\MSSQL\DATA\ecommerceDB.ldf' , SIZE = 8192KB , MAXSIZE = 2048GB , FILEGROWTH = 10%)
 WITH CATALOG_COLLATION = DATABASE_DEFAULT, LEDGER = OFF
GO
ALTER DATABASE [ecommerceDB] SET COMPATIBILITY_LEVEL = 160
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [ecommerceDB].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [ecommerceDB] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [ecommerceDB] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [ecommerceDB] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [ecommerceDB] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [ecommerceDB] SET ARITHABORT OFF 
GO
ALTER DATABASE [ecommerceDB] SET AUTO_CLOSE ON 
GO
ALTER DATABASE [ecommerceDB] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [ecommerceDB] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [ecommerceDB] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [ecommerceDB] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [ecommerceDB] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [ecommerceDB] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [ecommerceDB] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [ecommerceDB] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [ecommerceDB] SET  DISABLE_BROKER 
GO
ALTER DATABASE [ecommerceDB] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [ecommerceDB] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [ecommerceDB] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [ecommerceDB] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [ecommerceDB] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [ecommerceDB] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [ecommerceDB] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [ecommerceDB] SET RECOVERY SIMPLE 
GO
ALTER DATABASE [ecommerceDB] SET  MULTI_USER 
GO
ALTER DATABASE [ecommerceDB] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [ecommerceDB] SET DB_CHAINING OFF 
GO
ALTER DATABASE [ecommerceDB] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [ecommerceDB] SET TARGET_RECOVERY_TIME = 60 SECONDS 
GO
ALTER DATABASE [ecommerceDB] SET DELAYED_DURABILITY = DISABLED 
GO
ALTER DATABASE [ecommerceDB] SET ACCELERATED_DATABASE_RECOVERY = OFF  
GO
ALTER DATABASE [ecommerceDB] SET QUERY_STORE = ON
GO
ALTER DATABASE [ecommerceDB] SET QUERY_STORE (OPERATION_MODE = READ_WRITE, CLEANUP_POLICY = (STALE_QUERY_THRESHOLD_DAYS = 30), DATA_FLUSH_INTERVAL_SECONDS = 900, INTERVAL_LENGTH_MINUTES = 60, MAX_STORAGE_SIZE_MB = 1000, QUERY_CAPTURE_MODE = AUTO, SIZE_BASED_CLEANUP_MODE = AUTO, MAX_PLANS_PER_QUERY = 200, WAIT_STATS_CAPTURE_MODE = ON)
GO
USE [ecommerceDB]
GO
/****** Object:  Table [dbo].[Activities]    Script Date: 05/03/2025 3:19:58 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[Activities](
	[ActivityID] [int] IDENTITY(1,1) NOT NULL,
	[UserID] [int] NOT NULL,
	[ActivityDescription] [nvarchar](255) NOT NULL,
	[ActivityDate] [datetime] NOT NULL,
	[Module] [nvarchar](50) NOT NULL,
 CONSTRAINT [PK_Activities] PRIMARY KEY CLUSTERED 
(
	[ActivityID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[admin_users]    Script Date: 05/03/2025 3:19:59 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[admin_users](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[username] [varchar](50) NOT NULL,
	[password] [varchar](255) NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_admin_users] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[cart]    Script Date: 05/03/2025 3:19:59 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[cart](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[user_id] [int] NOT NULL,
	[product_id] [int] NOT NULL,
	[quantity] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
	[product_size] [nvarchar](50) NULL,
 CONSTRAINT [PK_cart] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[notifications]    Script Date: 05/03/2025 3:19:59 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[notifications](
	[notif_id] [int] IDENTITY(1,1) NOT NULL,
	[user_id] [int] NOT NULL,
	[message] [varchar](255) NOT NULL,
	[is_read] [int] NOT NULL,
	[created_at] [datetime] NOT NULL,
 CONSTRAINT [PK_notification] PRIMARY KEY CLUSTERED 
(
	[notif_id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[order_items]    Script Date: 05/03/2025 3:19:59 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[order_items](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[order_id] [int] NOT NULL,
	[product_name] [varchar](255) NOT NULL,
	[quantity] [int] NOT NULL,
	[price] [decimal](10, 2) NOT NULL,
	[product_size] [nvarchar](50) NULL,
 CONSTRAINT [PK_order_item] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[orders]    Script Date: 05/03/2025 3:19:59 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[orders](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[user_id] [int] NOT NULL,
	[total_price] [decimal](10, 2) NOT NULL,
	[shippingAddress] [nvarchar](255) NOT NULL,
	[status] [nvarchar](50) NULL,
	[payment_method] [nvarchar](50) NOT NULL,
	[shippingfee] [decimal](10, 2) NULL,
	[order_date] [datetime] NOT NULL,
	[notified] [bit] NULL,
 CONSTRAINT [PK_order] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[products]    Script Date: 05/03/2025 3:19:59 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[products](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [nvarchar](255) NOT NULL,
	[description] [nvarchar](max) NOT NULL,
	[price] [decimal](10, 2) NOT NULL,
	[stock] [int] NOT NULL,
	[category] [varchar](50) NOT NULL,
	[image_path] [varchar](255) NOT NULL,
	[image_path_2] [varchar](255) NULL,
	[created_at] [datetime] NOT NULL,
	[updated_at] [datetime] NOT NULL,
 CONSTRAINT [PK_products] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO
/****** Object:  Table [dbo].[successfulpayment]    Script Date: 05/03/2025 3:19:59 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[successfulpayment](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[order_id] [int] NOT NULL,
	[customer_name] [nvarchar](255) NOT NULL,
	[total_price] [decimal](10, 2) NOT NULL,
	[order_date] [datetime] NOT NULL,
	[payment_method] [nvarchar](50) NOT NULL,
	[shipping_address] [nvarchar](255) NOT NULL,
	[created_at] [datetime] NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
/****** Object:  Table [dbo].[users1 ]    Script Date: 05/03/2025 3:19:59 pm ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[users1 ](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[username] [nvarchar](100) NOT NULL,
	[email] [nvarchar](255) NOT NULL,
	[password] [nvarchar](100) NOT NULL,
	[created_at] [datetime] NOT NULL,
	[reset_token] [nvarchar](255) NULL,
	[token_expiry] [datetime] NULL,
 CONSTRAINT [PK_users] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO
ALTER TABLE [dbo].[orders] ADD  CONSTRAINT [DF_order_status_1]  DEFAULT (N'Pending') FOR [status]
GO
ALTER TABLE [dbo].[orders] ADD  CONSTRAINT [DF_order_shippingfee]  DEFAULT ((0)) FOR [shippingfee]
GO
ALTER TABLE [dbo].[orders] ADD  CONSTRAINT [DF_order_notified]  DEFAULT ((0)) FOR [notified]
GO
ALTER TABLE [dbo].[successfulpayment] ADD  DEFAULT (getdate()) FOR [created_at]
GO
ALTER TABLE [dbo].[Activities]  WITH CHECK ADD  CONSTRAINT [FK_Activities_users1] FOREIGN KEY([UserID])
REFERENCES [dbo].[users1 ] ([id])
GO
ALTER TABLE [dbo].[Activities] CHECK CONSTRAINT [FK_Activities_users1]
GO
ALTER TABLE [dbo].[cart]  WITH CHECK ADD  CONSTRAINT [FK_cart_users1] FOREIGN KEY([user_id])
REFERENCES [dbo].[users1 ] ([id])
GO
ALTER TABLE [dbo].[cart] CHECK CONSTRAINT [FK_cart_users1]
GO
ALTER TABLE [dbo].[notifications]  WITH CHECK ADD  CONSTRAINT [FK_notifications_users1 ] FOREIGN KEY([user_id])
REFERENCES [dbo].[users1 ] ([id])
GO
ALTER TABLE [dbo].[notifications] CHECK CONSTRAINT [FK_notifications_users1 ]
GO
ALTER TABLE [dbo].[order_items]  WITH CHECK ADD  CONSTRAINT [FK_order_items_orders] FOREIGN KEY([order_id])
REFERENCES [dbo].[orders] ([id])
GO
ALTER TABLE [dbo].[order_items] CHECK CONSTRAINT [FK_order_items_orders]
GO
ALTER TABLE [dbo].[orders]  WITH CHECK ADD  CONSTRAINT [FK_orders_users1 ] FOREIGN KEY([user_id])
REFERENCES [dbo].[users1 ] ([id])
GO
ALTER TABLE [dbo].[orders] CHECK CONSTRAINT [FK_orders_users1 ]
GO
ALTER TABLE [dbo].[products]  WITH CHECK ADD  CONSTRAINT [FK_products_products] FOREIGN KEY([id])
REFERENCES [dbo].[products] ([id])
GO
ALTER TABLE [dbo].[products] CHECK CONSTRAINT [FK_products_products]
GO
ALTER TABLE [dbo].[successfulpayment]  WITH CHECK ADD  CONSTRAINT [FK_successfulpayment_order] FOREIGN KEY([order_id])
REFERENCES [dbo].[orders] ([id])
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[successfulpayment] CHECK CONSTRAINT [FK_successfulpayment_order]
GO
USE [master]
GO
ALTER DATABASE [ecommerceDB] SET  READ_WRITE 
GO
