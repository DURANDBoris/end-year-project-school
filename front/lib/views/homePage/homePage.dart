import 'package:flutter/material.dart';
import 'package:front/views/appBar/MyAppBar.dart';
import 'package:front/views/homePage/homePageContent/homePageContent.dart';

class HomePage extends StatefulWidget {
  const HomePage({Key? key}) : super(key: key);

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: MyAppBar(),
      body: HomePageContent(),
    );
  }
}
